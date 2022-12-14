<?php

require_once(ROOT . 'models/Vlak.php');

class Prijava_vlaka extends Vlak {

    private $_id_vlaka;
    private $_izlozba_id;
    private $_azurirao_moderator_id;
    private $_datum_azuriranja;
    private $_id_statusa;
    private $_status;
    private $_id_korisnika;
    private $_ime_korisnika;
    private $_prezime_korisnika;
    private $_korisnicko_ime;
    private $_email;
    private $_id_tematike;
    private $_naziv_tematike;
    private $_datum_pocetka_izlozbe;
    private $_vazi_do;
    private $_broj_korisnika;
    private $_trenutni_broj_korisnika;
    private $_url_slike;

    public function dohvati_id_vlaka() {
        return $this->_id_vlaka;
    }

    public function postavi_id_vlaka($id_vlaka) {
        if (empty($id_vlaka)) {
            throw new Exception("Niste popunili id vlaka.");
        }
        $this->_id_vlaka = $id_vlaka;
    }

    public function dohvati_izlozba_id() {
        return $this->_izlozba_id;
    }

    public function postavi_izlozba_id($izlozba_id) {
        if (empty($izlozba_id)) {
            throw new Exception("Niste popunili id izlozbe.");
        }
        $this->_izlozba_id = $izlozba_id;
    }

    public function dohvati_azurirao_moderator_id() {
        return $this->_azurirao_moderator_id;
    }

    public function postavi_azurirao_moderator_id($azurirao_moderator_id) {
        $this->_azurirao_moderator_id = $azurirao_moderator_id;
    }

    public function dohvati_datum_azuriranja() {
        return $this->_datum_azuriranja;
    }

    public function postavi_datum_azuriranja($datum_azuriranja) {
        $this->_datum_azuriranja = $datum_azuriranja;
    }

    public function dohvati_id_statusa() {
        return $this->_id_statusa;
    }

    public function postavi_id_statusa($id_statusa) {
        if (empty($id_statusa)) {
            throw new Exception("Niste popunili id statusa.");
        }
        $this->_id_statusa = $id_statusa;
    }

    public function dohvati_status() {
        return $this->_status;
    }

    public function postavi_status($status) {
        $this->_status = $status;
    }

    public function dohvati_id_korisnika() {
        return $this->_id_korisnika;
    }

    public function postavi_id_korisnika($id_korisnika) {
        $this->_id_korisnika = $id_korisnika;
    }

    public function dohvati_ime_korisnika() {
        return $this->_ime_korisnika;
    }

    public function postavi_ime_korisnika($ime_korisnika) {
        $this->_ime_korisnika = $ime_korisnika;
    }

    public function dohvati_prezime_korisnika() {
        return $this->_prezime_korisnika;
    }

    public function postavi_prezime_korisnika($prezime_korisnika) {
        $this->_prezime_korisnika = $prezime_korisnika;
    }

    public function dohvati_korisnicko_ime() {
        return $this->_korisnicko_ime;
    }

    public function postavi_korisnicko_ime($korisnicko_ime) {
        $this->_korisnicko_ime = $korisnicko_ime;
    }
    
    public function dohvati_email() {
        return $this->_email;
    }

    public function postavi_email($email) {
        $this->_email = $email;
    }

    public function dohvati_id_tematike() {
        return $this->_id_tematike;
    }

    public function postavi_id_tematike($id_tematike) {
        $this->_id_tematike = $id_tematike;
    }

    public function dohvati_naziv_tematike() {
        return $this->_naziv_tematike;
    }

    public function postavi_naziv_tematike($naziv_tematike) {
        $this->_naziv_tematike = $naziv_tematike;
    }

    public function dohvati_datum_pocetka_izlozbe() {
        return $this->_datum_pocetka_izlozbe;
    }

    public function postavi_datum_pocetka_izlozbe($datum_pocetka_izlozbe) {
        $this->_datum_pocetka_izlozbe = $datum_pocetka_izlozbe;
    }

    public function dohvati_vazi_do() {
        return $this->_vazi_do;
    }

    public function postavi_vazi_do($vazi_do) {
        $this->_vazi_do = $vazi_do;
    }

    public function dohvati_broj_korisnika() {
        return $this->_broj_korisnika;
    }

    public function postavi_broj_korisnika($broj_korisnika) {
        $this->_broj_korisnika = $broj_korisnika;
    }

    public function dohvati_trenutni_broj_korisnika() {
        return $this->_trenutni_broj_korisnika;
    }

    public function postavi_trenutni_broj_korisnika($trenutni_broj_korisnika) {
        $this->_trenutni_broj_korisnika = $trenutni_broj_korisnika;
    }

    public function dohvati_url_slike() {
        return $this->_url_slike;
    }

    public function postavi_url_slike($url_slike) {
        $this->_url_slike = $url_slike;
    }

}

abstract class Prihvacivanje_korisnika {

    const PRIHVATI = 1;
    const ODBIJ = 2;

}
