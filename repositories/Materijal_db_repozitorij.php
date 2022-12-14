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
include_once(ROOT . 'repositories/interfaces/Materijal_repozitorij_interface.php');
include_once(ROOT . 'repositories/Dnevnik_db_repozitorij.php');
include_once(ROOT . 'repositories/database/Baza.php');
include_once(ROOT . 'utils/Postavke.php');
include_once (ROOT . 'models/Materijal.php');
include_once (ROOT . 'models/Dnevnik.php');

class Materijal_db_repozitorij implements Materijal_repozitorij_interface {

    private $_db;
    private $_dnevnik;
    private $_korisnicko_ime;

    public function __construct() {
        $this->_db = Baza::dohvati_instancu();
        $this->_korisnicko_ime = Sesija::dohvati_korisnicko_ime();
        $this->_dnevnik = new Dnevnik_db_repozitorij();
    }

    public function dohvati_prema_id($id) {
        throw new Exception('Onemogućena funkcionalnost.');
    }

    public function kreiraj($uneseni_podaci) {
        $materijal = new Materijal;

        $materijal->url = $uneseni_podaci["url"];
        $materijal->vrsta_materijala = $uneseni_podaci["vrsta_materijala"];
        $materijal->prijava_vlaka = $uneseni_podaci["id_prijave_vlaka"];

        $sql = "INSERT INTO materijal (url, vrsta_materijala_id, prijava_vlaka_id) VALUES "
                . "('{$materijal->url}', {$materijal->vrsta_materijala}, {$materijal->prijava_vlaka})";

        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function dohvati_listu() {
        $sql = "SELECT * FROM materijal";
        $rezultat_sqla_materijala = $this->_db->dohvati_podatke($sql);

        $popis_materijala = array();
        foreach ($rezultat_sqla_materijala as $model_sqla) {
            $materijal = new Materijal;

            $materijal->id = $model_sqla["id"];
            $materijal->url = $model_sqla["url"];
            $materijal->vrsta_materijala = $model_sqla["vrsta_materijala_id"];
            $materijal->prijava_vlaka = $model_sqla["prijava_vlaka_id"];

            array_push($popis_materijala, $materijal);
        }
        return $popis_materijala;
    }

    public function obrisi($id_zapisa) {
        throw new Exception('Onemogućena funkcionalnost.');
    }

    public function azuriraj($uneseni_podaci) {
        throw new Exception('Onemogućena funkcionalnost.');
    }

    public function dohvati_materijale_prema_modelu($id_prijave_vlaka, $format) {
        $sql = "SELECT id, url FROM materijal WHERE "
                . "prijava_vlaka_id = {$id_prijave_vlaka} AND "
                . "vrsta_materijala_id = {$format}";
        $rezultat_sqla_materijala = $this->_db->dohvati_podatke($sql);

        $popis_materijala = array();
        foreach ($rezultat_sqla_materijala as $model_sqla) {
            $materijal = new Materijal;

            $materijal->id = $model_sqla["id"];
            $materijal->url = $model_sqla["url"];

            array_push($popis_materijala, $materijal);
        }
        return $popis_materijala;
    }

    public function zapisi_podatke_iz_sigurosne_kopije($zapis_materijala) {
        $sql = "INSERT INTO materijal VALUES " . $zapis_materijala;
        $this->_db->zapisi_podatke($sql);
        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
    }

}
