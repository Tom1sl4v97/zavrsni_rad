<?php

require_once(ROOT . 'models/Model.php');

class Materijal extends Model {

    private $_url;
    private $_vrsta_materijala;
    private $_prijava_vlaka;

    public function dohvati_url() {
        return $this->_url;
    }

    public function postavi_url($url) {
        if (empty($url)) {
            throw new Exception("Niste popunili url materijala.");
        }
        $this->_url = $url;
    }

    public function dohvati_vrsta_materijala() {
        return $this->_vrsta_materijala;
    }

    public function postavi_vrsta_materijala($vrsta_materijala) {
        if (empty($vrsta_materijala)) {
            throw new Exception("Niste popunili id vrste materijala.");
        }
        $this->_vrsta_materijala = $vrsta_materijala;
    }

    public function dohvati_prijava_vlaka() {
        return $this->_prijava_vlaka;
    }

    public function postavi_prijava_vlaka($prijava_vlaka) {
        if (empty($prijava_vlaka)) {
            throw new Exception("Niste popunili id prijave vlaka.");
        }
        $this->_prijava_vlaka = $prijava_vlaka;
    }

}
