<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of korisniciDbRepozitorij
 *
 * @author franj
 */
include_once(ROOT . 'repositories/interfaces/Glasovanje_repozitorij_interface.php');
include_once(ROOT . 'repositories/Dnevnik_db_repozitorij.php');
include_once(ROOT . 'repositories/database/Baza.php');
include_once(ROOT . 'utils/Postavke.php');
include_once (ROOT . 'models/Glasovanje.php');
include_once (ROOT . 'models/Dnevnik.php');

class Glasovanje_db_repozitorij implements Glasovanje_repozitorij_interface {

    private $_db;
    private $_dnevnik;
    private $_koriscnisko_ime;

    public function __construct() {
        $this->_db = Baza::dohvati_instancu();
        $this->_koriscnisko_ime = Sesija::dohvati_korisnicko_ime();
        $this->_dnevnik = new Dnevnik_db_repozitorij();
    }

    public function dohvati_prema_id($id) {
        throw new Exception('Onemogućena funkcionalnost.');
    }

    public function kreiraj($uneseni_podaci) {
        $glasovanje = $this->_kreiraj_model_glasovanje($uneseni_podaci);

        $sql = "INSERT INTO "
                . "glasovanje (vazi_od, vazi_do, izlozba_id) "
                . "VALUES ("
                . "'{$glasovanje->vazi_od}', "
                . "'{$glasovanje->vazi_do}', "
                . "{$glasovanje->izlozba_id})";

        $this->_dnevnik->kreiraj_dnevnik($this->_koriscnisko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    private function _kreiraj_model_glasovanje($uneseni_podaci) {
        $glasovanje = new Glasovanje;

        $glasovanje->vazi_od = $uneseni_podaci["pocetakGlasovanja"];
        $glasovanje->vazi_do = $uneseni_podaci["zavrsetakGlasovanja"];
        $glasovanje->izlozba_id = $uneseni_podaci["id_izlozbe"];

        return $glasovanje;
    }

    public function dohvati_listu() {
        throw new Exception('Onemogućena funkcionalnost.');
    }

    public function obrisi($id_zapisa) {
        throw new Exception('Onemogućena funkcionalnost.');
    }

    public function azuriraj($uneseni_podaci) {
        $glasovanje = $this->_kreiraj_model_glasovanje($uneseni_podaci);

        $sql = "UPDATE glasovanje SET "
                . "vazi_od = '{$glasovanje->vazi_od}', "
                . "vazi_do = '{$glasovanje->vazi_do}' "
                . "WHERE izlozba_id = {$glasovanje->izlozba_id}";

        $this->_dnevnik->kreiraj_dnevnik($this->_koriscnisko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function dohvati_glasovanje_prema_izlozbi($id_izlozbe) {
        $sql = "SELECT vazi_od, vazi_do FROM glasovanje WHERE izlozba_id={$id_izlozbe}";
        $rezultat_sqla_glasovanja = $this->_db->dohvati_podatke($sql);

        $glasovanje = new Glasovanje;

        $glasovanje->vazi_od = $rezultat_sqla_glasovanja[0]["vazi_od"];
        $glasovanje->vazi_do = $rezultat_sqla_glasovanja[0]["vazi_do"];

        return $glasovanje;
    }

}
