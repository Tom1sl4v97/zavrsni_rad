<?php

require_once(ROOT . 'models/Model.php');

class Vrsta_pogona extends Model {

    private $_naziv_pogona;
    private $_opis_pogona;

    public function dohvati_naziv_pogona() {
        return $this->_naziv_pogona;
    }

    public function postavi_naziv_pogona($naziv_pogona) {
        if (empty($naziv_pogona)) {
            throw new Exception("Niste popunili naziv pogona");
        }
        $this->_naziv_pogona = $naziv_pogona;
    }

    public function dohvati_opis_pogona() {
        return $this->_opis_pogona;
    }

    public function postavi_opis_pogona($opis_pogona) {
        if (empty($opis_pogona)) {
            throw new Exception("Niste popunili opis pogona");
        }
        $this->_opis_pogona = $opis_pogona;
    }

}
