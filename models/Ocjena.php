<?php

require_once(ROOT . 'models/Model.php');

class Ocjena extends Model {

    private $_ocjena_korisnika;
    private $_komentar;
    private $_prijava_vlaka_id;
    private $_korisnik_id;

    public function dohvati_ocjena_korisnika() {
        return $this->_ocjena_korisnika;
    }

    public function postavi_ocjena_korisnika($ocjena_korisnika) {
        if (empty($ocjena_korisnika)){
            throw new Exception("Niste popunili ocjenu korisnika.");
        }
        $this->_ocjena_korisnika = $ocjena_korisnika;
    }

    public function dohvati_komentar() {
        return $this->_komentar;
    }

    public function postavi_komentar($komentar) {
        $this->_komentar = $komentar;
    }

    public function dohvati_prijava_vlaka_id() {
        return $this->_prijava_vlaka_id;
    }

    public function postavi_prijava_vlaka_id($prijava_vlaka_id) {
        if (empty($prijava_vlaka_id)){
            throw new Exception("Niste popunili id prijave vlaka.");
        }
        $this->_prijava_vlaka_id = $prijava_vlaka_id;
    }

    public function dohvati_korisnik_id() {
        return $this->_korisnik_id;
    }

    public function postavi_korisnik_id($korisnik_id) {
        if (empty($korisnik_id)){
            throw new Exception("Niste popunili id korisnika.");
        }
        $this->_korisnik_id = $korisnik_id;
    }

}
