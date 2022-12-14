<?php

require_once(ROOT . 'models/Vrsta_pogona.php');

class Vlak extends Vrsta_pogona {

    private $_naziv;
    private $_max_brzina;
    private $_broj_sjedala;
    private $_opis;
    private $_vrsta_pogona_id;
    private $_vlasnik_id;
    private $_naziv_pogona;

    public function dohvati_naziv() {
        return $this->_naziv;
    }

    public function postavi_naziv($naziv) {
        if (empty($naziv)) {
            throw new Exception("Niste unijeli naziv vlaka.");
        }
        $this->_naziv = $naziv;
    }

    public function dohvati_max_brzina() {
        return $this->_max_brzina;
    }

    public function postavi_max_brzina($max_brzina) {
        if (empty($max_brzina) OR!preg_match('/^[0-9]+$/', $max_brzina)) {
            throw new Exception("Molimo Vas da unesete pozitivan prirodni broj za maksimalnu brzinu vlaka.");
        }
        $this->_max_brzina = $max_brzina;
    }

    public function dohvati_broj_sjedala() {
        return $this->_broj_sjedala;
    }

    public function postavi_broj_sjedala($broj_sjedala) {
        if (empty($broj_sjedala) OR!preg_match('/^[0-9]+$/', $broj_sjedala)) {
            throw new Exception("Molimo Vas da unesete pozitivan prirodni broj za broj sjedala vlaka.");
        }
        $this->_broj_sjedala = $broj_sjedala;
    }

    public function dohvati_opis() {
        return $this->_opis;
    }

    public function postavi_opis($opis) {
        if (empty($opis)) {
            throw new Exception("Niste unijeli opis vlaka.");
        }
        $this->_opis = $opis;
    }

    public function dohvati_vrsta_pogona_id() {
        return $this->_vrsta_pogona_id;
    }

    public function postavi_vrsta_pogona_id($vrsta_pogona_id) {
        if (empty($vrsta_pogona_id)) {
            throw new Exception("Niste odabrali vrstu pogona vlaka.");
        }
        $this->_vrsta_pogona_id = $vrsta_pogona_id;
    }

    public function dohvati_vlasnik_id() {
        return $this->_vlasnik_id;
    }

    public function postavi_vlasnik_id($vlasnik_id) {
        if (empty($vlasnik_id)) {
            throw new Exception("Niste odabrali vlasnika vlaka.");
        }
        $this->_vlasnik_id = $vlasnik_id;
    }

    public function dohvati_naziv_pogona() {
        return $this->_naziv_pogona;
    }

    public function postavi_naziv_pogona($naziv_pogona) {
        $this->_naziv_pogona = $naziv_pogona;
    }

}
