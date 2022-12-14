<?php

class Tematika extends Model {

    private $_naziv;
    private $_opis;
    private $_kreirao_korisnik_id;
    private $_korisnik_kreiranja;
    private $_datum_kreiranja;
    private $_azurirao_korisnik_id;
    private $_korisnik_azuriranja;
    private $_datum_azuriranja;

    public function dohvati_naziv() {
        return $this->_naziv;
    }

    public function postavi_naziv($naziv) {
        if (empty($naziv)){
            throw new Exception("Niste unijeli naziv tematike.");
        }
        $this->_naziv = $naziv;
    }

    public function dohvati_opis() {
        return $this->_opis;
    }

    public function postavi_opis($opis) {
        if (empty($opis)){
            throw new Exception("Niste unijeli opis tematike.");
        }
        $this->_opis = $opis;
    }

    public function dohvati_kreirao_korisnik_id() {
        return $this->_kreirao_korisnik_id;
    }

    public function postavi_kreirao_korisnik_id($kreirao_korisnik_id) {
        if (empty($kreirao_korisnik_id)) {
            throw new Exception("Niste popunili id kreatora tematike.");
        }
        $this->_kreirao_korisnik_id = $kreirao_korisnik_id;
    }

    public function dohvati_korisnik_kreiranja() {
        return $this->_korisnik_kreiranja;
    }

    public function postavi_korisnik_kreiranja($korisnik_kreiranja) {
        if (empty($korisnik_kreiranja)) {
            throw new Exception("Niste popunili naziv kreatora tematike.");
        }
        $this->_korisnik_kreiranja = $korisnik_kreiranja;
    }

    public function dohvati_datum_kreiranja() {
        return $this->_datum_kreiranja;
    }

    public function postavi_datum_kreiranja($datum_kreiranja) {
        if (empty($datum_kreiranja)) {
            throw new Exception("Niste popunili datum kreiranja tematike.");
        }
        $this->_datum_kreiranja = $datum_kreiranja;
    }

    public function dohvati_azurirao_korisnik_id() {
        return $this->_azurirao_korisnik_id;
    }

    public function postavi_azurirao_korisnik_id($azurirao_korisnik_id) {
        $this->_azurirao_korisnik_id = $azurirao_korisnik_id;
    }

    public function dohvati_korisnik_azuriranja() {
        return $this->_korisnik_azuriranja;
    }

    public function postavi_korisnik_azuriranja($korisnik_azuriranja) {
        $this->_korisnik_azuriranja = $korisnik_azuriranja;
    }

    public function dohvati_datum_azuriranja() {
        return $this->_datum_azuriranja;
    }

    public function postavi_datum_azuriranja($datum_azuriranja) {
        $this->_datum_azuriranja = $datum_azuriranja;
    }

}
