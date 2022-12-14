<?php

require_once(ROOT . 'models/Model.php');

class Glasovanje extends Model {

    private $_vazi_od;
    private $_vazi_do;
    private $_izlozba_id;

    public function dohvati_vazi_od() {
        return $this->_vazi_od;
    }

    public function postavi_vazi_od($vazi_od) {
        $this->_vazi_od = $vazi_od;
    }

    public function dohvati_vazi_do() {
        return $this->_vazi_do;
    }

    public function postavi_vazi_do($vazi_do) {
        $this->_vazi_do = $vazi_do;
    }

    public function dohvati_izlozba_id() {
        return $this->_izlozba_id;
    }

    public function postavi_izlozba_id($izlozba_id) {
        $this->_izlozba_id = $izlozba_id;
    }

}
