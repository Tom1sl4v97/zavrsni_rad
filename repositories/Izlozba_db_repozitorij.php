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
include_once(ROOT . 'repositories/interfaces/Izlozba_repozitorij_interface.php');
include_once(ROOT . 'repositories/Dnevnik_db_repozitorij.php');
include_once(ROOT . 'repositories/database/Baza.php');
include_once(ROOT . 'utils/Postavke.php');
include_once (ROOT . 'models/Izlozba.php');
include_once (ROOT . 'models/Dnevnik.php');

class Izlozba_db_repozitorij implements Izlozba_repozitorij_interface {

    private $_db;
    private $_virtualno_vrijeme;
    private $_dnevnik;
    private $_korisnicko_ime;

    public function __construct() {
        $this->_db = Baza::dohvati_instancu();
        $this->_virtualno_vrijeme = Postavke::dohvati_virtualno_vrijeme();
        $this->_korisnicko_ime = Sesija::dohvati_korisnicko_ime();
        $this->_dnevnik = new Dnevnik_db_repozitorij();
    }

    public function dohvati_prema_id($id) {
        $sql = "SELECT id, datum_pocetka, broj_korisnika, tematika_id"
                . " FROM izlozba WHERE izlozba.id = {$id}";
        $rezultat_sqla_izlozbe = $this->_db->dohvati_podatke($sql);

        $izlozba = new Izlozba;

        $izlozba->id = $rezultat_sqla_izlozbe[0]["id"];
        $izlozba->datum_pocetka = $rezultat_sqla_izlozbe[0]["datum_pocetka"];
        $izlozba->broj_korisnika = $rezultat_sqla_izlozbe[0]["broj_korisnika"];
        $izlozba->tematika_id = $rezultat_sqla_izlozbe[0]["tematika_id"];

        return $izlozba;
    }

    public function kreiraj($uneseni_podaci) {
        $izlozba = new Izlozba;

        $izlozba->datum_pocetka = $uneseni_podaci["datumPocetka"];
        $izlozba->broj_korisnika = $uneseni_podaci["maxBrojKorisnika"];
        $izlozba->tematika_id = $uneseni_podaci["odabirModeratoraTematike"];
        $izlozba->moderator_id = $uneseni_podaci["id_moderatora"];
        $izlozba->datum_kreiranja = Postavke::dohvati_virtualno_vrijeme();

        $sql = "INSERT INTO izlozba "
                . "(datum_pocetka, broj_korisnika, tematika_id, moderator_id, datum_kreiranja) VALUES "
                . "('{$izlozba->datum_pocetka}', "
                . "{$izlozba->broj_korisnika}, "
                . "{$izlozba->tematika_id}, "
                . "{$izlozba->moderator_id}, "
                . "'{$izlozba->datum_kreiranja}')";

        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function dohvati_listu() {
        $sql = "SELECT izlozba.id, izlozba.datum_pocetka, izlozba.broj_korisnika, t_id.naziv, t_id.opis, izlozba.moderator_id, "
                . " m_id.korisnicko_ime, getStatusIzlozbe(izlozba.id, '{$this->_virtualno_vrijeme}') AS 'status'"
                . " FROM izlozba"
                . " INNER JOIN tematika t_id ON t_id.id = izlozba.tematika_id"
                . " INNER JOIN korisnik m_id ON izlozba.moderator_id = m_id.id ORDER BY t_id.naziv";
        $rezultat_sqla_izlozbe = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_izlozba($rezultat_sqla_izlozbe);
    }

    private function _kreiraj_model_izlozba($rezultat_sqla_izlozbe) {
        $popis_izlozba = array();

        foreach ($rezultat_sqla_izlozbe as $model_sqla) {
            $izlozba = new Izlozba;

            $izlozba->id = $model_sqla["id"];
            $izlozba->datum_pocetka = $model_sqla["datum_pocetka"];
            $izlozba->broj_korisnika = $model_sqla["broj_korisnika"];
            $izlozba->naziv_tematike = $model_sqla["naziv"];
            $izlozba->opis_tematike = $model_sqla["opis"];
            $izlozba->moderator_id = $model_sqla["moderator_id"];
            $izlozba->korisnicko_ime_moderatora = $model_sqla["korisnicko_ime"];
            $izlozba->status_izlozbe = $model_sqla["status"];

            array_push($popis_izlozba, $izlozba);
        }

        return $popis_izlozba;
    }

    public function obrisi($id_zapisa) {
        $sql = "DELETE FROM izlozba WHERE id = {$id_zapisa}";
        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function azuriraj($uneseni_podaci) {
        $izlozba = new Izlozba;

        $izlozba->id = $uneseni_podaci["id_izlozbe"];
        $izlozba->datum_pocetka = $uneseni_podaci["datumPocetka"];
        $izlozba->broj_korisnika = $uneseni_podaci["maxBrojKorisnika"];
        $izlozba->tematika_id = $uneseni_podaci["odabirModeratoraTematike"];
        $izlozba->moderator_id = $uneseni_podaci["id_moderatora"];
        $izlozba->datum_azuriranja = Postavke::dohvati_virtualno_vrijeme();


        $sql = "UPDATE izlozba SET "
                . "datum_pocetka = '{$izlozba->datum_pocetka}', "
                . "broj_korisnika = {$izlozba->broj_korisnika}, "
                . "tematika_id = {$izlozba->tematika_id}, "
                . "moderator_id = {$izlozba->moderator_id}, "
                . "datum_azuriranja = '{$izlozba->datum_azuriranja}' "
                . "WHERE id = {$izlozba->id}";

        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function dohvati_listu_izlozbi_moderatora($korisnicko_ime) {
        $sql = "SELECT izlozba.id, izlozba.datum_pocetka, izlozba.broj_korisnika, t_id.naziv, t_id.opis, izlozba.moderator_id, "
                . "getStatusIzlozbe(izlozba.id, '{$this->_virtualno_vrijeme}') AS 'status', k_id.korisnicko_ime "
                . "FROM izlozba "
                . "INNER JOIN tematika t_id ON t_id.id = izlozba.tematika_id "
                . "INNER JOIN moderatori m_id ON m_id.tematika_id = t_id.id "
                . "INNER JOIN korisnik k_id ON k_id.id = m_id.moderator_id "
                . "WHERE k_id.korisnicko_ime = '{$korisnicko_ime}' AND m_id.vazi_od <= '{$this->_virtualno_vrijeme}'";
        $rezultat_sqla_izlozbe_moderatora = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_izlozba($rezultat_sqla_izlozbe_moderatora);
    }

    public function dohvati_id_izlozbe_prema_modelu($uneseni_podaci) {
        $izlozba = new Izlozba;

        $izlozba->datum_pocetka = $uneseni_podaci["datumPocetka"];
        $izlozba->broj_korisnika = $uneseni_podaci["maxBrojKorisnika"];
        $izlozba->tematika_id = $uneseni_podaci["odabirModeratoraTematike"];

        $sql = "SELECT id FROM izlozba WHERE "
                . "datum_pocetka = '{$izlozba->datum_pocetka}' AND "
                . "broj_korisnika = '{$izlozba->broj_korisnika}' AND "
                . "tematika_id = {$izlozba->tematika_id}";

        return $this->_db->dohvati_podatke($sql)[0]["id"];
    }

    public function dohvati_aktualne_izlozbe($id_izlozbe = "") {
        $sql = "SELECT i.id, t.naziv, t.opis, i.datum_pocetka, i.broj_korisnika, "
                . "CASE WHEN SUM(pv.status_id=1) IS NULL THEN 0 ELSE SUM(pv.status_id=1) END AS 'trenutni', "
                . "getStatusIzlozbe(i.id, '{$this->_virtualno_vrijeme}') AS 'status', g.id AS 'IDGlasovanja' "
                . "FROM izlozba i "
                . "INNER JOIN tematika t ON i.tematika_id = t.id "
                . "INNER JOIN glasovanje g ON g.izlozba_id = i.id "
                . "LEFT JOIN prijavavlaka pv ON pv.izlozba_id = i.id ";
        if (!empty($id_izlozbe)) {
            $sql .= "WHERE i.id = {$id_izlozbe} ";
        }
        $sql .= "GROUP BY i.id, t.naziv, t.opis, i.datum_pocetka, i.broj_korisnika, g.id "
                . "ORDER BY i.datum_pocetka";
        $rezultat_sqla_aktualnih_izlozbi = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_aktualne_izlozbe($rezultat_sqla_aktualnih_izlozbi);
    }

    private function _kreiraj_model_aktualne_izlozbe($rezultat_sqla_aktualnih_izlozbi) {
        $aktualne_izlozbe = array();

        foreach ($rezultat_sqla_aktualnih_izlozbi as $model_sqla) {
            $izlozba = new Izlozba;

            $izlozba->id = $model_sqla["id"];
            $izlozba->naziv_tematike = $model_sqla["naziv"];
            $izlozba->opis_tematike = $model_sqla["opis"];
            $izlozba->datum_pocetka = $model_sqla["datum_pocetka"];
            $izlozba->broj_korisnika = $model_sqla["broj_korisnika"];
            $izlozba->trenutni_broj_korisnika = $model_sqla["trenutni"];
            $izlozba->status_izlozbe = $model_sqla["status"];

            array_push($aktualne_izlozbe, $izlozba);
        }

        return $aktualne_izlozbe;
    }

    public function dohvati_zavrsene_izlozbe() {
        $sql = "SELECT i.id, t.naziv, t.opis, i.datum_pocetka, i.broj_korisnika, "
                . "CASE WHEN SUM(pv.status_id=1) IS NULL THEN 0 ELSE SUM(pv.status_id=1) END AS 'trenutni', "
                . "getStatusIzlozbe(i.id, '{$this->_virtualno_vrijeme}') AS 'status', g.id AS 'id_glasovanja' "
                . "FROM izlozba i "
                . "INNER JOIN tematika t ON i.tematika_id = t.id "
                . "INNER JOIN glasovanje g ON g.izlozba_id = i.id "
                . "LEFT JOIN prijavavlaka pv ON pv.izlozba_id = i.id "
                . "WHERE getStatusIzlozbe(i.id, '{$this->_virtualno_vrijeme}') = 'Zatvoreno glasovanje' "
                . "GROUP BY i.id, t.naziv, t.opis, i.datum_pocetka, i.broj_korisnika, g.id "
                . "ORDER BY i.datum_pocetka";
        $rezultat_sqla_zavrsenih_izlozbi = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_zavrsenih_izlozba($rezultat_sqla_zavrsenih_izlozbi);
    }

    private function _kreiraj_model_zavrsenih_izlozba($rezultat_sqla_zavrsenih_izlozbi) {
        $popis_zavrsenih_izlozba = array();

        foreach ($rezultat_sqla_zavrsenih_izlozbi as $model_sqla) {
            $izlozba = new Izlozba;

            $izlozba->id = $model_sqla["id"];
            $izlozba->naziv_tematike = $model_sqla["naziv"];
            $izlozba->opis_tematike = $model_sqla["opis"];
            $izlozba->datum_pocetka = $model_sqla["datum_pocetka"];
            $izlozba->broj_korisnika = $model_sqla["broj_korisnika"];
            $izlozba->trenutni_broj_korisnika = $model_sqla["trenutni"];
            $izlozba->status_izlozbe = $model_sqla["status"];
            $izlozba->id_glasovanja = $model_sqla["id_glasovanja"];

            array_push($popis_zavrsenih_izlozba, $izlozba);
        }
        return $popis_zavrsenih_izlozba;
    }

    public function dohvati_pobjednika_izlozbe($id_izlozbe) {
        $sql = "SELECT k.ime, k.prezime, k.korisnicko_ime, k.email, v.naziv, "
                . "SUM(pv.status_id) AS 'ukupno_glasova', SUM(o.ocjena_korisnika) AS 'ukupno_bodova', pv.id AS 'id_prijave_vlaka' "
                . "FROM izlozba i "
                . "INNER JOIN prijavavlaka pv ON pv.izlozba_id = i.id "
                . "INNER JOIN ocjena o ON o.prijava_vlaka_id = pv.id "
                . "INNER JOIN vlak v ON v.id = pv.vlak_id "
                . "INNER JOIN korisnik k ON v.vlasnik_id = k.id "
                . "WHERE pv.izlozba_id = {$id_izlozbe} "
                . "GROUP BY k.ime, k.prezime, k.korisnicko_ime, k.email, pv.id, v.naziv "
                . "ORDER BY ukupno_glasova DESC, ukupno_bodova DESC";
        $rezultat_sqla_pobjednika_izlozbe = $this->_db->dohvati_podatke($sql);


        return $this->_kreiraj_model_pobjednika_izlozbe($rezultat_sqla_pobjednika_izlozbe);
    }

    private function _kreiraj_model_pobjednika_izlozbe($rezultat_sqla_pobjednika_izlozbe) {
        $popis_rezultata_glasanja = array();
        foreach ($rezultat_sqla_pobjednika_izlozbe as $model_sqla) {
            $izlozba = new Izlozba;

            $izlozba->ime = $model_sqla["ime"];
            $izlozba->prezime = $model_sqla["prezime"];
            $izlozba->korisnicko_ime = $model_sqla["korisnicko_ime"];
            $izlozba->email = $model_sqla["email"];
            $izlozba->naziv_vlaka = $model_sqla["naziv"];
            $izlozba->ukupno_glasova = $model_sqla["ukupno_glasova"];
            $izlozba->ukupno_bodova = $model_sqla["ukupno_bodova"];
            $izlozba->id_prijave_vlaka = $model_sqla["id_prijave_vlaka"];

            array_push($popis_rezultata_glasanja, $izlozba);
        }
        return $popis_rezultata_glasanja;
    }

}
