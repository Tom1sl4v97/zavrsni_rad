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
include_once(ROOT . 'repositories/interfaces/Vlak_repozitorij_interface.php');
include_once(ROOT . 'repositories/Dnevnik_db_repozitorij.php');
include_once(ROOT . 'repositories/database/Baza.php');
include_once(ROOT . 'models/Vlak.php');
include_once(ROOT . 'utils/Postavke.php');

class Vlak_db_repozitorij implements Vlak_repozitorij_interface {

    private $_db;
    private $_dnevnik;
    private $_korisnicko_ime;

    public function __construct() {
        $this->_db = Baza::dohvati_instancu();
        $this->_dnevnik = new Dnevnik_db_repozitorij;
        $this->_korisnicko_ime = Sesija::dohvati_korisnicko_ime();
    }

    public function dohvati_prema_id($id_vlaka) {
        $sql = "SELECT * FROM vlak v WHERE v.id = {$id_vlaka}";
        $rezultat_sqla_vlaka = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_vlaka_od_baze($rezultat_sqla_vlaka[0]);
    }

    private function _kreiraj_model_vlaka_od_baze($rezultat_sqla_vlaka) {
        $vlak = new Vlak;

        $vlak->id = $rezultat_sqla_vlaka["id"];
        $vlak->naziv = $rezultat_sqla_vlaka["naziv"];
        $vlak->max_brzina = $rezultat_sqla_vlaka["max_brzina"];
        $vlak->broj_sjedala = $rezultat_sqla_vlaka["broj_sjedala"];
        $vlak->opis = $rezultat_sqla_vlaka["opis"];
        $vlak->vrsta_pogona_id = $rezultat_sqla_vlaka["vrsta_pogona_id"];
        $vlak->vlasnik_id = $rezultat_sqla_vlaka["vlasnik_id"];

        return $vlak;
    }

    public function kreiraj($uneseni_podaci) {
        $vlak = $this->_kreiraj_model_vlaka_od_fronta($uneseni_podaci);

        $sql = "INSERT INTO vlak (naziv, max_brzina, broj_sjedala, opis, vrsta_pogona_id, vlasnik_id) VALUES "
                . "('{$vlak->naziv}', {$vlak->max_brzina}, {$vlak->broj_sjedala}, "
                . "'{$vlak->opis}', {$vlak->vrsta_pogona_id}, {$vlak->vlasnik_id})";

        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    private function _kreiraj_model_vlaka_od_fronta($uneseni_podaci) {
        $vlak = new Vlak;

        if (isset($uneseni_podaci["id_vlaka"])) {
            $vlak->id = $uneseni_podaci["id_vlaka"];
        }
        $vlak->naziv = $uneseni_podaci["nazivVlaka"];
        $vlak->max_brzina = $uneseni_podaci["maxBrzina"];
        $vlak->broj_sjedala = $uneseni_podaci["brojSjedala"];
        $vlak->opis = $uneseni_podaci["opisVlaka"];
        $vlak->vrsta_pogona_id = $uneseni_podaci["vrstaPognona"];
        $vlak->vlasnik_id = $uneseni_podaci["vlasnik_id"];

        return $vlak;
    }

    public function dohvati_listu() {
        $sql = "SELECT * FROM vlak";
        $rezultat_sqla_vlaka = $this->_db->dohvati_podatke($sql);

        $popis_vlakova = array();
        foreach ($rezultat_sqla_vlaka as $model_sqla) {
            array_push($popis_vlakova, $this->_kreiraj_model_vlaka_od_baze($model_sqla));
        }
        return $popis_vlakova;
    }

    public function obrisi($id_zapisa) {
        $sql = "DELETE FROM vlak WHERE id = {$id_zapisa}";
        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function azuriraj($uneseni_podaci) {
        $vlak = $this->_kreiraj_model_vlaka_od_fronta($uneseni_podaci);

        $sql = "UPDATE vlak SET "
                . "naziv = '{$vlak->naziv}', "
                . "max_brzina = {$vlak->max_brzina}, "
                . "broj_sjedala = {$vlak->broj_sjedala}, "
                . "opis = '{$vlak->opis}', "
                . "vrsta_pogona_id = {$vlak->vrsta_pogona_id}, "
                . "vlasnik_id = {$vlak->vlasnik_id} "
                . "WHERE id = {$vlak->id}";

        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function prikaz_vlakova_korisnika() {
        $sql = "SELECT v.id, v.naziv, v.max_brzina, v.broj_sjedala, v.opis, vp.naziv_pogona"
                . " FROM vlak v INNER JOIN korisnik k ON v.vlasnik_id = k.id"
                . " INNER JOIN vrstapogona vp ON v.vrsta_pogona_id = vp.id"
                . " WHERE k.korisnicko_ime = '{$this->_korisnicko_ime}'";
        $rezultat_sqla_vlakovi_korisnika = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_vlakova_korisnika($rezultat_sqla_vlakovi_korisnika);
    }

    private function _kreiraj_model_vlakova_korisnika($rezultat_sqla_vlakovi_korisnika) {
        $popis_vlakova_korisnika = array();
        foreach ($rezultat_sqla_vlakovi_korisnika as $model_sqla) {
            $vlak = new Vlak;

            $vlak->id = $model_sqla["id"];
            $vlak->naziv = $model_sqla["naziv"];
            $vlak->max_brzina = $model_sqla["max_brzina"];
            $vlak->broj_sjedala = $model_sqla["broj_sjedala"];
            $vlak->opis = $model_sqla["opis"];
            $vlak->naziv_pogona = $model_sqla["naziv_pogona"];

            array_push($popis_vlakova_korisnika, $vlak);
        }
        return $popis_vlakova_korisnika;
    }

    function dohvati_slobodne_vlakove_korisnika($id_izlozbe) {
        $sql = "SELECT v.id AS 'IDVlaka', v.naziv  "
                . "FROM vlak v "
                . "INNER JOIN korisnik k ON v.vlasnik_id = k.id "
                . "WHERE k.korisnicko_ime = '{$this->_korisnicko_ime}' AND "
                . "     v.id NOT IN (SELECT pv.vlak_id "
                . "                  FROM prijavavlaka pv JOIN izlozba i ON i.id = pv.izlozba_id "
                . "                  WHERE pv.vlak_id = v.id AND pv.izlozba_id = {$id_izlozbe})";
        $rezultat_sqla_slobodni_vlak_korisnika = $this->_db->dohvati_podatke($sql);
        
        return $this->_kreiraj_model_slobodnih_vlakova_korisnika($rezultat_sqla_slobodni_vlak_korisnika);
    }

    private function _kreiraj_model_slobodnih_vlakova_korisnika($rezultat_sqla_slobodni_vlak_korisnika) {
        $popis_vlakova = array();
        foreach ($rezultat_sqla_slobodni_vlak_korisnika as $model_sqla) {
            $vlak = new Vlak;

            $vlak->id = $model_sqla["IDVlaka"];
            $vlak->naziv = $model_sqla["naziv"];

            array_push($popis_vlakova, $vlak);
        }
        return $popis_vlakova;
    }

    public function zapisi_podatke_iz_sigurosne_kopije($zapis) {
        $sql = "INSERT INTO vlak VALUES " . $zapis;
        $this->_db->zapisi_podatke($sql);
        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
    }

    public function obrisi_sve_vlakove() {
        $sql = "DELETE FROM vlak";
        $this->_db->zapisi_podatke($sql);
        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
    }

}
