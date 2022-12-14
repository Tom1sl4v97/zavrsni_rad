<?php

require_once(ROOT . 'models/Korisnik.php');

class Izlozba extends Korisnik {

    private $_datum_pocetka;
    private $_broj_korisnika;
    private $_trenutni_broj_korisnika;
    private $_tematika_id;
    private $_naziv_tematike;
    private $_opis_tematike;
    private $_moderator_id;
    private $_korisnicko_ime_moderatora;
    private $_datum_kreiranja;
    private $_datum_azuriranja;
    private $_status_izlozbe;
    private $_id_glasovanja;
    private $_naziv_vlaka;
    private $_ukupno_glasova;
    private $_ukupno_bodova;
    private $_id_prijave_vlaka;

    public function dohvati_datum_pocetka() {
        return $this->_datum_pocetka;
    }

    public function postavi_datum_pocetka($datum_pocetka) {
        if (empty($datum_pocetka)) {
            throw new Exception("Niste popunili datum pocetka izlozbe.");
        }
        $this->_datum_pocetka = $datum_pocetka;
    }

    public function dohvati_broj_korisnika() {
        return $this->_broj_korisnika;
    }

    public function postavi_broj_korisnika($broj_korisnika) {
        if (empty($broj_korisnika)) {
            throw new Exception("Niste popunili maksimalni broj korisnika.");
        }
        $this->_broj_korisnika = $broj_korisnika;
    }

    public function dohvati_trenutni_broj_korisnika() {
        return $this->_trenutni_broj_korisnika;
    }

    public function postavi_trenutni_broj_korisnika($trenutni_broj_korisnika) {
        $this->_trenutni_broj_korisnika = $trenutni_broj_korisnika;
    }

    public function dohvati_tematika_id() {
        return $this->_tematika_id;
    }

    public function postavi_tematika_id($tematika_id) {
        if (empty($tematika_id)) {
            throw new Exception("Niste popunili id tematike.");
        }
        $this->_tematika_id = $tematika_id;
    }

    public function dohvati_naziv_tematike() {
        return $this->_naziv_tematike;
    }

    public function postavi_naziv_tematike($naziv_tematike) {
        $this->_naziv_tematike = $naziv_tematike;
    }

    public function dohvati_opis_tematike() {
        return $this->_opis_tematike;
    }

    public function postavi_opis_tematike($opis_tematike) {
        $this->_opis_tematike = $opis_tematike;
    }

    public function dohvati_moderator_id() {
        return $this->_moderator_id;
    }

    public function postavi_moderator_id($moderator_id) {
        if (empty($moderator_id)) {
            throw new Exception("Niste popunili id moderatora.");
        }
        $this->_moderator_id = $moderator_id;
    }

    public function dohvati_korisnicko_ime_moderatora() {
        return $this->_korisnicko_ime_moderatora;
    }

    public function postavi_korisnicko_ime_moderatora($korisnicko_ime_moderatora) {
        $this->_korisnicko_ime_moderatora = $korisnicko_ime_moderatora;
    }

    public function dohvati_datum_kreiranja() {
        return $this->_datum_kreiranja;
    }

    public function postavi_datum_kreiranja($datum_kreiranja) {
        if (empty($datum_kreiranja)) {
            throw new Exception("Niste popunili datum kreiranja izlozbe.");
        }
        $this->_datum_kreiranja = $datum_kreiranja;
    }

    public function dohvati_datum_azuriranja() {
        return $this->_datum_azuriranja;
    }

    public function postavi_datum_azuriranja($datum_azuriranja) {
        $this->_datum_azuriranja = $datum_azuriranja;
    }

    public function dohvati_status_izlozbe() {
        return $this->_status_izlozbe;
    }

    public function postavi_status_izlozbe($status_izlozbe) {
        $this->_status_izlozbe = $status_izlozbe;
    }

    public function dohvati_id_glasovanja() {
        return $this->_id_glasovanja;
    }

    public function postavi_id_glasovanja($id_glasovanja) {
        $this->_id_glasovanja = $id_glasovanja;
    }

    public function dohvati_naziv_vlaka() {
        return $this->_naziv_vlaka;
    }

    public function postavi_naziv_vlaka($naziv_vlaka) {
        $this->_naziv_vlaka = $naziv_vlaka;
    }

    public function dohvati_ukupno_glasova() {
        return $this->_ukupno_glasova;
    }

    public function postavi_ukupno_glasova($ukupno_glasova) {
        $this->_ukupno_glasova = $ukupno_glasova;
    }

    public function dohvati_ukupno_bodova() {
        return $this->_ukupno_bodova;
    }

    public function postavi_ukupno_bodova($ukupno_bodova) {
        $this->_ukupno_bodova = $ukupno_bodova;
    }

    public function dohvati_id_prijave_vlaka() {
        return $this->_id_prijave_vlaka;
    }

    public function postavi_id_prijave_vlaka($id_prijave_vlaka) {
        $this->_id_prijave_vlaka = $id_prijave_vlaka;
    }

}
