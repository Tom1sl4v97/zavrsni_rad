<?php

class Model {

    private $id;

    private function dohvati_id() {
        return $this->id;
    }

    private function postavi_id($id) {
        $this->id = $id;
    }

    public function __set($ime, $vrijednost) {
        $ime_funkcije = 'postavi_' . $ime;
        return $this->$ime_funkcije($vrijednost);
    }

    public function __get($ime) {
        $ime_funkcije = 'dohvati_' . $ime;
        return $this->$ime_funkcije();
    }

}
