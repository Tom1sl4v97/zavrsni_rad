<?php

class Baza {

    const server = "localhost";
    const korisnik = "root";
    const lozinka = "";
    const baza = "zavrsnirad";

    private static $instanca;
    private $veza = NULL;
    private $greska = '';

    private function __construct() {
        // Private kako bi se onemogućila inicijalizacija - singleton 
    }

    /**
     * Kreira instancu DB klijenta
     * 
     * @return baza
     */
    public static function dohvati_instancu() {
        if (is_null(static::$instanca)) {
            static::$instanca = new Baza;
        }

        return static::$instanca;
    }

    final public function __clone() {
        throw new Exception('Onemogućena funkcionalnost.');
    }

    final public function __wakeup() {
        throw new Exception('Onemogućena funkcionalnost.');
    }

    function spoji_db() {
        $this->veza = new mysqli(self::server, self::korisnik, self::lozinka, self::baza);
        if ($this->veza->connect_errno) {
            echo "Neuspješno spajanje na bazu: " . $this->veza->connect_errno . ", " .
            $this->veza->connect_error;
            $this->greska = $this->veza->connect_error;
        }
        $this->veza->set_charset("utf8");
        if ($this->veza->connect_errno) {
            echo "Neuspješno postavljanje znakova za bazu: " . $this->veza->connect_errno . ", " .
            $this->veza->connect_error;
            $this->greska = $this->veza->connect_error;
        }
        return $this->veza;
    }

    function zatvori_db() {
        $this->veza->close();
    }

    function select_db($upit) {
        $rezultat = $this->veza->query($upit);
        if ($this->veza->connect_errno) {
            echo "Greška kod upita: {$upit} - " . $this->veza->connect_errno . ", " .
            $this->veza->connect_error;
            $this->greska = $this->veza->connect_error;
        }
        if (!$rezultat) {
            $rezultat = NULL;
        }
        return $rezultat;
    }

    function update_db($upit, $skripta = '') {
        $rezultat = $this->veza->query($upit);
        if ($this->veza->connect_errno) {
            echo "Greška kod upita: {$upit} - " . $this->veza->connect_errno . ", " .
            $this->veza->connect_error;
            $this->greska = $this->veza->connect_error;
        } else {
            if ($skripta != '') {
                echo "<script>window.location.href='$skripta';</script>";
            }
        }
        return $rezultat;
    }

    function zapisi_podatke($sql) {
        $this->spoji_db();
        $provjera_upisa = $this->update_db($sql);
        $this->zatvori_db();
        if (!$provjera_upisa) {
            return FALSE;
        }
        return TRUE;
    }

    function dohvati_podatke($sql) {
        $this->spoji_db();
        $rezultat = $this->select_db($sql);
        $redovi = mysqli_fetch_all($rezultat, MYSQLI_ASSOC);
        $this->zatvori_db();
        return $redovi;
    }

}

?>