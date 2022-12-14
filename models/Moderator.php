<?php

require_once(ROOT . 'models/Model.php');

class Moderator extends Model {

    private $_administrator_id;
    private $_korisnicko_ime_administratora;
    private $_moderator_id;
    private $_korisnicko_ime_moderatora;
    private $_tematika_id;
    private $_naziv_tematike;
    private $_opis_tematike;
    private $_vazi_od;
    private $_vazi_do;

    public function dohvati_administrator_id() {
        return $this->_administrator_id;
    }

    public function postavi_administrator_id($administrator_id) {
        if (empty($administrator_id)) {
            throw new Exception("Niste odabrali id administratora.");
        }
        $this->_administrator_id = $administrator_id;
    }

    public function dohvati_korisnicko_ime_administratora() {
        return $this->_korisnicko_ime_administratora;
    }

    public function postavi_korisnicko_ime_administratora($korisnicko_ime_administratora) {
        $this->_korisnicko_ime_administratora = $korisnicko_ime_administratora;
    }

    public function dohvati_moderator_id() {
        return $this->_moderator_id;
    }

    public function postavi_moderator_id($moderator_id) {
        if (empty($moderator_id)) {
            throw new Exception("Niste odabrali id moderatora.");
        }
        $this->_moderator_id = $moderator_id;
    }

    public function dohvati_korisnicko_ime_moderatora() {
        return $this->_korisnicko_ime_moderatora;
    }

    public function postavi_korisnicko_ime_moderatora($korisnicko_ime_moderatora) {
        $this->_korisnicko_ime_moderatora = $korisnicko_ime_moderatora;
    }

    public function dohvati_tematika_id() {
        return $this->_tematika_id;
    }

    public function postavi_tematika_id($tematika_id) {
        if (empty($tematika_id)) {
            throw new Exception("Niste odabrali id tematike.");
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

    public function dohvati_vazi_od() {
        return $this->_vazi_od;
    }

    public function postavi_vazi_od($vazi_od) {
        if (empty($vazi_od)) {
            throw new Exception("Niste odabrali od kada vrijedi moderator.");
        }
        $this->_vazi_od = $vazi_od;
    }

    public function dohvati_vazi_do() {
        return $this->_vazi_do;
    }

    public function postavi_vazi_do($vazi_do) {
        $datum = empty($vazi_do) ? "NULL" : $vazi_do;
        $this->_vazi_do = $datum;
    }

}
