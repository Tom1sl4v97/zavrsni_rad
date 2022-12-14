<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pocetna_stranica_controller
 *
 * @author franj
 */
include_once 'Controller.php';
include_once(ROOT . 'repositories/Korisnici_db_repozitorij.php');
include_once(ROOT . 'repositories/Dnevnik_db_repozitorij.php');
include_once(ROOT . 'models/Dnevnik.php');

class Korisnici_controller extends Controller {

    private $_dnevnik_repo;
    private $_virtualno_vrijeme;
    private $_korisnici_repo;
    private $_url_servera;
    private $_korisnicko_ime;

    public function __construct() {
        parent::__construct();
        $this->_dnevnik_repo = new Dnevnik_db_repozitorij();
        $this->_virtualno_vrijeme = Postavke::dohvati_virtualno_vrijeme();
        $this->_korisnici_repo = new Korisnici_db_repozitorij();
        $this->_url_servera = Postavke::dohvati_server_url();
        $this->_korisnicko_ime = Sesija::dohvati_korisnicko_ime();
    }

    function prijava_korisnika() {
        try {
            $popis_gresaka = array("korime" => "korisnicko ime", "lozinka" => "lozinku");
            $uneseni_podaci = $this->provjeri_popunjesnost_obaveznih_podataka($popis_gresaka);

            if (!$uneseni_podaci) {
                return $this->podaci_na_prijavu();
            }

            $korisnik = $this->_korisnici_repo->dohvati_prema_korisnicko_ime($uneseni_podaci["korime"]);
            $korisnik->lozinka = $uneseni_podaci["lozinka"];

            $this->_kreiraj_kolacic($uneseni_podaci);

            if ($korisnik->datum_kreiranja != "") {
                $this->_provjeri_podatke_kod_prijave($korisnik);
            } else {
                throw new Exception("Molimo aktivirajte svoj račun.");
            }
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->podaci_na_prijavu();
        }
    }

    function podaci_na_prijavu($sesija = "") {
        $podaci_fronta["naslov_stranice"] = "Prijava korisnika";
        $podaci_fronta["opis_stranice"] = "Ovo je stranica prijave korisnika, kreirana 18.3.2021.";
        $podaci_fronta["korisnicko_ime"] = $this->_provjeri_zapamcenost_korisnika();

        if ($sesija == "istekla_sesija") {
            $this->pripremi_greske("Istekla Vam je sesija, ponovno se ulogirajte.");
        }

        if ($this->_korisnik_je_prijavljen()) {
            $this->preusmjeri("/Projektni_zadatak/pocetna_stranica/index/");
        }

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("prijava");
    }

    private function _provjeri_zapamcenost_korisnika() {
        if (isset($_COOKIE["korisnik"]) and $_COOKIE["korisnik"] !== "zaboravljen") {
            return $_COOKIE["korisnik"];
        }
        return FALSE;
    }

    private function _korisnik_je_prijavljen() {
        if (isset($this->_korisnicko_ime)) {
            return TRUE;
        }
        return FALSE;
    }

    private function _kreiraj_kolacic($uneseni_podaci) {
        $virtualno_vrijeme = strtotime($this->_virtualno_vrijeme);
        if (isset($uneseni_podaci["zapamtiMe"])) {
            setcookie("korisnik", $uneseni_podaci["korime"], $virtualno_vrijeme + Postavke::dohvati_txt_zapis_dokumenta("trajanje_kolacica") * 24 * 60 * 60, "/");
        } else {
            setcookie("korisnik", "zaboravljen", $virtualno_vrijeme + Postavke::dohvati_txt_zapis_dokumenta("trajanje_kolacica") * 24 * 60 * 60, "/");
        }
    }

    private function _provjeri_podatke_kod_prijave($korisnik) {
        if ($this->_podaci_prijave_su_ispravni($korisnik)) {
            $this->_pripremi_podatke_korisnika($korisnik);
        } elseif ($korisnik->broj_neuspijesnih_prijava >= 3) {
            throw new Exception("Korisniči račun  {$korisnik->korisnicko_ime} Vam je blokiran. Molimo Vas kontaktirajte administratora");
        } else {
            $this->_trazi_ponovnu_prijavu($korisnik);
        }
    }

    private function _podaci_prijave_su_ispravni($korisnik) {
        $heshirana_lozinka = hash("sha256", $korisnik->lozinka . $korisnik->salt);

        return $heshirana_lozinka === $korisnik->lozinka_sha1 and $korisnik->broj_neuspijesnih_prijava <= 3;
    }

    private function _pripremi_podatke_korisnika($korisnik) {
        Sesija::kreiraj_korisnika($korisnik->korisnicko_ime, $this->_virtualno_vrijeme, $korisnik->tip_korisnika_id);
        $korisnik->broj_neuspijesnih_prijava = 0;
        $this->_korisnici_repo->azuriraj($korisnik);
        $this->preusmjeri($this->_url_servera . "pocetna_stranica/index/");
    }

    private function _trazi_ponovnu_prijavu($korisnik) {
        $korisnik->broj_neuspijesnih_prijava++;
        $this->_korisnici_repo->azuriraj($korisnik);
        throw new Exception("Neispravna lozinka, pokušajte ponovno.");
    }

    function registrtiraj_korisnika() {
        try {
            $popis_gresaka = array("ime", "prezime", "recaptcha" => "recaptcha", "korisnicko_ime" => "korisničko ime",
                "email" => "e-mail", "lozinka" => "prvu lozinku", "lozinka2" => "drugu lozinku");
            $uneseni_podaci = $this->provjeri_popunjesnost_obaveznih_podataka($popis_gresaka);

            if (!$uneseni_podaci) {
                return $this->prebaci_na_registraciju();
            }

            if ($uneseni_podaci["lozinka"] === $uneseni_podaci["lozinka2"]) {
                $this->_validiraj_podatke_kod_registracije($uneseni_podaci);
            } else {
                throw new Exception("Lozinke su vam različite.");
            }
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prebaci_na_registraciju();
        }
    }

    private function _validiraj_podatke_kod_registracije($uneseni_podataci) {
        $postojeci_korisnik = $this->_korisnici_repo->dohvati_prema_korisnicko_ime($uneseni_podataci["korisnicko_ime"]);
        if (!$postojeci_korisnik) {
            $this->_korisnici_repo->kreiraj($uneseni_podataci);
            $this->_pripremi_korisnicki_racun($uneseni_podataci);
            throw new Exception("Poslali smo Vam aktivacijski link na mail.");
        } else {
            throw new Exception("Korisnicko ime je već zauzeto, molimo odaberite drugo korisnicko ime");
        }
    }

    private function _pripremi_korisnicki_racun($uneseni_podataci) {
        $url = $this->_url_servera . "pocetna_stranica/aktiviraj_korisnicki_racun/{$uneseni_podataci["korisnicko_ime"]}/";
        $poruka_korisniku = "Molimo vas kliknite na sljedeci link za potvrdu korisnickom racuna, {$url}";
        $mail_korisniku = wordwrap($poruka_korisniku, 120);
        mail("{$uneseni_podataci["email"]}", "Aktivacijski link", $mail_korisniku);
    }

    function prebaci_na_registraciju() {
        $podaci_fronta["naslov_stranice"] = "Registracija korisnika";
        $podaci_fronta["opis_stranice"] = "Ovo je stranica registracija novih korisnika, kreirana 18.3.2021.";

        if ($this->_korisnik_je_prijavljen()) {
            $this->preusmjeri("/Projektni_zadatak/pocetna_stranica/index/");
        }

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("registracija");
    }

    function prijava_tesnog_korisnika($tip_korisnika) {
        switch ($tip_korisnika) {
            case 1:
                Sesija::kreiraj_korisnika("ttomiek", Postavke::dohvati_virtualno_vrijeme(), 1, Sesija::dohvati_dizajn_korisnika(), Sesija::dohvati_darkmode_korisnika());
                break;
            case 2:
                Sesija::kreiraj_korisnika("dtokic", Postavke::dohvati_virtualno_vrijeme(), 2, Sesija::dohvati_dizajn_korisnika(), Sesija::dohvati_darkmode_korisnika());
                break;
            case 3:
                Sesija::kreiraj_korisnika("mmarulic", Postavke::dohvati_virtualno_vrijeme(), 3, Sesija::dohvati_dizajn_korisnika(), Sesija::dohvati_darkmode_korisnika());
                break;
        }
        $this->preusmjeri("/Projektni_zadatak/pocetna_stranica/index");
    }

    function odjava() {
        $this->_dnevnik_repo->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::PRIJAVA_ODJAVA);
        Sesija::obrisi_sesiju();
        $this->preusmjeri("/Projektni_zadatak/pocetna_stranica/index");
    }

    function prihvacivanje_uvjeta_koristenja($id_korisnika = "") {
        if (!empty($id_korisnika)) {
            $this->_korisnici_repo->prihvacivanje_uvjeta_koristenja($id_korisnika);
        }
        $this->preusmjeri($this->_url_servera . "pocetna_stranica/index/");
    }

}
