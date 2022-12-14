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
include_once(ROOT . 'repositories/interfaces/Ocjena_repozitorij_interface.php');
include_once(ROOT . 'repositories/Dnevnik_db_repozitorij.php');
include_once(ROOT . 'repositories/database/Baza.php');
include_once(ROOT . 'utils/Postavke.php');
include_once(ROOT . 'models/Ocjena.php');
include_once(ROOT . 'models/Dnevnik.php');

class Ocjena_db_repozitorij implements Ocjena_repozitorij_interface {

    private $_db;
    private $_dnevnik;
    private $_korisnicko_ime;

    public function __construct() {
        $this->_db = Baza::dohvati_instancu();
        $this->_dnevnik = new Dnevnik_db_repozitorij();
        $this->_korisnicko_ime = Sesija::dohvati_korisnicko_ime();
    }

    public function dohvati_prema_id($id) {
        throw new Exception('Onemogućena funkcionalnost.');
    }

    public function kreiraj($uneseni_podaci) {
        $ocjena = new Ocjena;

        $ocjena->prijava_vlaka_id = $uneseni_podaci["prijava_vlaka_id"];
        $ocjena->komentar = $uneseni_podaci["komentar"];
        $ocjena->ocjena_korisnika = $uneseni_podaci["ocjena"];

        $sql = "INSERT INTO ocjena (prijava_vlaka_id, komentar, ocjena_korisnika, korisnik_id) "
                . "VALUES ({$ocjena->prijava_vlaka_id}, '{$ocjena->komentar}',  {$ocjena->ocjena_korisnika}, "
                . "(SELECT id FROM korisnik WHERE korisnicko_ime = '{$this->_korisnicko_ime}' LIMIT 1))";

        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function dohvati_listu() {
        $sql = "SELECT * FROM ocjena";
        $rezultat_sqla_ocjene = $this->_db->dohvati_podatke($sql);

        $popis_ocjene = array();
        foreach ($rezultat_sqla_ocjene as $model_sqla) {
            $ocjena = new Ocjena;

            $ocjena->id = $model_sqla["id"];
            $ocjena->ocjena_korisnika = $model_sqla["ocjena_korisnika"];
            $ocjena->komentar = $model_sqla["komentar"];
            $ocjena->prijava_vlaka_id = $model_sqla["prijava_vlaka_id"];
            $ocjena->korisnik_id = $model_sqla["korisnik_id"];

            array_push($popis_ocjene, $ocjena);
        }
        return $popis_ocjene;
    }

    public function obrisi($id_zapisa) {
        throw new Exception('Onemogućena funkcionalnost.');
    }

    public function azuriraj($uneseni_podaci) {
        $ocjena = new Ocjena;

        $ocjena->id = $uneseni_podaci["id_ocjene"];
        $ocjena->prijava_vlaka_id = $uneseni_podaci["prijava_vlaka_id"];
        $ocjena->komentar = $uneseni_podaci["komentar"];
        $ocjena->ocjena_korisnika = $uneseni_podaci["ocjena"];

        $sql = "UPDATE ocjena "
                . "SET  ocjena_korisnika={$ocjena->ocjena_korisnika}, "
                . "komentar='{$ocjena->komentar}' "
                . "WHERE prijava_vlaka_id = {$ocjena->prijava_vlaka_id} "
                . "AND ocjena.id = {$ocjena->id} "
                . "AND korisnik_id IN (SELECT id FROM korisnik WHERE korisnicko_ime = '{$this->_korisnicko_ime}')";

        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function dohvati_id_ocjene_prema_modelu($id_prijave_vlaka) {
        $sql = "SELECT id FROM `ocjena` WHERE "
                . "prijava_vlaka_id = {$id_prijave_vlaka} AND "
                . "korisnik_id IN (SELECT id FROM korisnik WHERE korisnicko_ime = '{$this->_korisnicko_ime}')";
        $rezultat_id_cjene = $this->_db->dohvati_podatke($sql);

        if (isset($rezultat_id_cjene[0]["id"])) {
            $ocjena = new Ocjena;
            $ocjena->id = $rezultat_id_cjene[0]["id"];
            return $ocjena->id;
        }
        return FALSE;
    }

    public function zapisi_podatke_iz_sigurosne_kopije($zapis_ocjene) {
        $sql = "INSERT INTO ocjena VALUES " . $zapis_ocjene;
        $this->_db->zapisi_podatke($sql);
        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
    }

}
