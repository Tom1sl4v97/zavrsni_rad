<?php

require_once(ROOT . 'models/Model.php');

class Vrsta_materijala extends Model {

    private $_format;

    public function dohvati_format() {
        return $this->_format;
    }

    public function postavi_format($format) {
        if (empty($format)) {
            throw new Exception("Niste odabrali format materijala.");
        }
        $this->_format = $format;
    }

}

abstract class format_materijala {

    const SLIKE = 1;
    const AUDIO = 2;
    const VIDEO = 3;
    const GIF = 4;

}
