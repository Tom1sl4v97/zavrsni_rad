<?php

require_once(ROOT . 'models/Korisnik.php');

class Dnevnik extends Korisnik {

    private $_stranica;
    private $_upit;
    private $_datum_pristupa;
    private $_tip_dnevnika_id;
    private $_korisnik_id;
    private $_tip_dnevnika_opis;

    public function dohvati_stranica() {
        return $this->_stranica;
    }

    public function postavi_stranica($stranica) {
        $this->_stranica = $stranica;
    }

    public function dohvati_upit() {
        return $this->_upit;
    }

    public function postavi_upit($upit) {
        $this->_upit = $upit;
    }

    public function dohvati_datum_pristupa() {
        return $this->_datum_pristupa;
    }

    public function postavi_datum_pristupa($datum_pristupa) {
        $this->_datum_pristupa = $datum_pristupa;
    }

    public function dohvati_tip_dnevnika_id() {
        return $this->_tip_dnevnika_id;
    }

    public function postavi_tip_dnevnika_id($tip_dnevnika_id) {
        $this->_tip_dnevnika_id = $tip_dnevnika_id;
    }

    public function dohvati_korisnik_id() {
        return $this->_korisnik_id;
    }

    public function postavi_korisnik_id($korisnik_id) {
        $this->_korisnik_id = $korisnik_id;
    }

    public function dohvati_tip_dnevnika_opis() {
        return $this->_tip_dnevnika_opis;
    }

    public function postavi_tip_dnevnika_opis($tip_dnevnika_opis) {
        $this->_tip_dnevnika_opis = $tip_dnevnika_opis;
    }
}

abstract class Tip_dnevnika {

    const PRIJAVA_ODJAVA = 1;
    const RAD_S_BAZOM = 2;
    const OSTALE_RADNJE = 3;

}
