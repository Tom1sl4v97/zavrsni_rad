<?php

require_once(ROOT . 'models/Model.php');
include_once(ROOT . 'repositories/Korisnici_db_repozitorij.php');

class Korisnik extends Model {

    private $_ime;
    private $_prezime;
    private $_korisnicko_ime;
    private $_lozinka;
    private $_lozinka_sha1;
    private $_email;
    private $_uvjeti_koristenja;
    private $_status;
    private $_tip_korisnika_id;
    private $_naziv_uloge;
    private $_broj_neuspijesnih_prijava;
    private $_salt;
    private $_datum_kreiranja;

    public function dohvati_ime() {
        return $this->_ime;
    }

    public function postavi_ime($ime) {
        $this->_ime = $ime;
    }

    public function dohvati_prezime() {
        return $this->_prezime;
    }

    public function postavi_prezime($prezime) {
        $this->_prezime = $prezime;
    }

    public function dohvati_korisnicko_ime() {
        return $this->_korisnicko_ime;
    }

    public function postavi_korisnicko_ime($korisnicko_ime) {
        if (empty($korisnicko_ime)) {
            throw new Exception("Niste unijeli korisnicko ime.");
        }
        $this->_korisnicko_ime = $korisnicko_ime;
    }

    public function dohvati_lozinka() {
        return $this->_lozinka;
    }

    public function postavi_lozinka($lozinka) {
        $uzorak = '/^(?!.*(.)\1{3})((?=.*[\d])(?=.*[A-Za-z])|(?=.*[^\w\d\s])(?=.*[A-Za-z])).{8,20}$/';
        if (empty($lozinka)) {
            throw new Exception("Niste unijeli lozinku.");
        }
        if (!preg_match($uzorak, $lozinka)) {
            throw new Exception("Format: Lozinka ima manje od 8 znakova "
                            . "ili više od 20 znakova "
                            . "ili nema 1 alfanumerički znak "
                            . "ili nema najmanje 1 broj "
                            . "ili nema specijalni znak "
                            . "ili se ponavljaju 3 ista znaka!"
                            . "<br>");
        }
        $this->_lozinka = $lozinka;
    }

    public function dohvati_lozinka_sha1() {
        return $this->_lozinka_sha1;
    }

    public function postavi_lozinka_sha1($lozinka_sha1) {
        $this->_lozinka_sha1 = $lozinka_sha1;
    }

    public function dohvati_email() {
        return $this->_email;
    }

    public function postavi_email($email) {
        if (empty($email)) {
            throw new Exception("Niste unijeli email.");
        }
        $this->_email = $email;
    }

    public function dohvati_uvjeti_koristenja() {
        return $this->_uvjeti_koristenja;
    }

    public function postavi_uvjeti_koristenja($uvjeti_koristenja) {
        $this->_uvjeti_koristenja = $uvjeti_koristenja;
    }

    public function dohvati_status() {
        return $this->_status;
    }

    public function postavi_status($status) {
        $this->_status = $status;
    }

    public function dohvati_tip_korisnika_id() {
        return $this->_tip_korisnika_id;
    }

    public function postavi_tip_korisnika_id($tip_korisnika_id) {
        $this->_tip_korisnika_id = $tip_korisnika_id;
    }

    public function dohvati_naziv_uloge() {
        return $this->_naziv_uloge;
    }

    public function postavi_naziv_uloge($naziv_uloge) {
        $this->_naziv_uloge = $naziv_uloge;
    }

    public function dohvati_broj_neuspijesnih_prijava() {
        return $this->_broj_neuspijesnih_prijava;
    }

    public function postavi_broj_neuspijesnih_prijava($broj_neuspijesnih_prijava) {
        $this->_broj_neuspijesnih_prijava = $broj_neuspijesnih_prijava;
    }

    public function dohvati_salt() {
        return $this->_salt;
    }

    public function postavi_salt($salt) {
        if (empty($salt)) {
            $text = md5(uniqid(rand(), TRUE));
            $this->_salt = substr($text, 0, 3);
        } else {
            $this->_salt = $salt;
        }
    }

    public function dohvati_datum_kreiranja() {
        return $this->_datum_kreiranja;
    }

    public function postavi_datum_kreiranja($datum_kreiranja) {
        $this->_datum_kreiranja = $datum_kreiranja;
    }

}

abstract class uloga_korisnika {

    const ADMINISTRATOR = 1;
    const MODERATOR = 2;
    const PRIJAVLJENI_KORISNIK = 3;
    const NE_PRIJAVLJENI_KORISNIK = 4;

}

abstract class de_blokiraj_korisnika {

    const OD_BLOKIRAJ_KORISNIKA = 0;
    const BLOKIRAJ_KORISNIKA = 3;

}
