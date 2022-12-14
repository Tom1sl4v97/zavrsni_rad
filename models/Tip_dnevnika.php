<?php

require_once(ROOT . 'models/Model.php');

class Tip_dnevnika extends Model {

    private $_opis;

    public function dohvati_opis() {
        return $this->_opis;
    }

    public function postavi_opis($opis) {
        if (empty($opis)) {
            throw new Exception("Niste popunili opis dnevnika");
        }
        $this->_opis = $opis;
    }

}
