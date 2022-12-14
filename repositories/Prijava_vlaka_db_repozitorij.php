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
include_once(ROOT . 'repositories/interfaces/Prijava_vlaka_repozitorij_interface.php');
include_once(ROOT . 'repositories/Korisnici_db_repozitorij.php');
include_once(ROOT . 'repositories/Dnevnik_db_repozitorij.php');
include_once(ROOT . 'repositories/database/Baza.php');
include_once(ROOT . 'utils/Postavke.php');
include_once(ROOT . 'models/Prijava_vlaka.php');

class Prijava_vlaka_db_repozitorij implements Prijava_vlaka_repozitorij_interface {

    private $_db;
    private $_virtualno_vrijeme;
    private $_dnevnik_repo;
    private $_korisnicko_ime;

    public function __construct() {
        $this->_db = Baza::dohvati_instancu();
        $this->_virtualno_vrijeme = Postavke::dohvati_virtualno_vrijeme();
        $this->_dnevnik_repo = new Dnevnik_db_repozitorij();
        $this->_korisnicko_ime = Sesija::dohvati_korisnicko_ime();
    }

    public function dohvati_prema_id($id) {
        throw new Exception('Onemogućena funkcionalnost.');
    }

    public function kreiraj($uneseni_podaci) {
        $prijava_vlaka = new Prijava_vlaka;

        $prijava_vlaka->id_vlaka = $uneseni_podaci["odabirVlakaZaPrijavu"];
        $prijava_vlaka->izlozba_id = $uneseni_podaci["id_izlozbe"];

        $sql = "INSERT INTO prijavavlaka (vlak_id, izlozba_id) VALUES ({$prijava_vlaka->id_vlaka}, {$prijava_vlaka->izlozba_id})";

        $this->_dnevnik_repo->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function dohvati_listu() {
        $sql = "SELECT * FROM prijavavlaka";
        $rezultat_sqla_prijave_vlaka = $this->_db->dohvati_podatke($sql);

        $popis_prijave_vlakova = array();
        foreach ($rezultat_sqla_prijave_vlaka as $model_sqla) {
            $prijava_vlaka = new Prijava_vlaka;

            $prijava_vlaka->id = $model_sqla["id"];
            $prijava_vlaka->id_vlaka = $model_sqla["vlak_id"];
            $prijava_vlaka->izlozba_id = $model_sqla["izlozba_id"];
            $prijava_vlaka->azurirao_moderator_id = $model_sqla["azurirao_moderator_id"];
            $prijava_vlaka->datum_azuriranja = $model_sqla["datum_azuriranja"];
            $prijava_vlaka->id_statusa = $model_sqla["status_id"];

            array_push($popis_prijave_vlakova, $prijava_vlaka);
        }
        return $popis_prijave_vlakova;
    }

    public function obrisi($id_zapisa) {
        $sql = "DELETE FROM prijavavlaka WHERE id = {$id_zapisa}";

        $this->_dnevnik_repo->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function azuriraj($uneseni_podaci) {
        $sql = "UPDATE prijavavlaka SET status_id = {$uneseni_podaci["prihvacivanje_korisnika"]} WHERE id = {$uneseni_podaci["id"]};";
        $this->_db->zapisi_podatke($sql);
        $this->_dnevnik_repo->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
    }

    public function prikaz_informacija_administratora() {
        $sql = "SELECT prijavavlaka.id, prijavavlaka.vlak_id AS 'id_vlaka', v_id.naziv AS 'naziv_vlaka', "
                . "k_id.id AS 'id_korisnika', k_id.ime, k_id.prezime, k_id.korisnicko_ime AS 'korime', "
                . "t_id.id AS 'id_tematike', t_id.naziv as 'naziv_tematike', prijavavlaka.status_id AS 'id_statusa', "
                . "s_id.status, i_id.datum_pocetka "
                . "FROM prijavavlaka "
                . "INNER JOIN vlak v_id ON prijavavlaka.vlak_id = v_id.id "
                . "INNER JOIN korisnik k_id ON k_id.id = v_id.vlasnik_id "
                . "INNER JOIN izlozba i_id ON prijavavlaka.izlozba_id = i_id.id "
                . "INNER JOIN tematika t_id ON i_id.tematika_id = t_id.id "
                . "INNER JOIN statusprijave s_id ON s_id.id = prijavavlaka.status_id";
        $rezultat_sqla_informacija_administratora = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_prijava_vlaka_administrator($rezultat_sqla_informacija_administratora);
    }

    private function _kreiraj_model_prijava_vlaka_administrator($rezultat_sqla_informacija_administratora) {
        $popis_prijava_vlakova = array();
        foreach ($rezultat_sqla_informacija_administratora as $model_sqla) {
            $prijava_vlaka = new Prijava_vlaka;

            $prijava_vlaka->id = $model_sqla["id"];
            $prijava_vlaka->id_vlaka = $model_sqla["id_vlaka"];
            $prijava_vlaka->naziv = $model_sqla["naziv_vlaka"];
            $prijava_vlaka->id_korisnika = $model_sqla["id_korisnika"];
            $prijava_vlaka->ime_korisnika = $model_sqla["ime"];
            $prijava_vlaka->prezime_korisnika = $model_sqla["prezime"];
            $prijava_vlaka->korisnicko_ime = $model_sqla["korime"];
            $prijava_vlaka->id_tematike = $model_sqla["id_tematike"];
            $prijava_vlaka->naziv_tematike = $model_sqla["naziv_tematike"];
            $prijava_vlaka->id_statusa = $model_sqla["id_statusa"];
            $prijava_vlaka->status = $model_sqla["status"];
            $prijava_vlaka->datum_pocetka_izlozbe = $model_sqla["datum_pocetka"];
            if (isset($model_sqla["vazi_do"])) {
                $prijava_vlaka->vazi_do = $model_sqla["vazi_do"];
            }
            array_push($popis_prijava_vlakova, $prijava_vlaka);
        }
        return $popis_prijava_vlakova;
    }

    public function prikaz_informacija_moderatora($korisnicko_ime) {
        $sql = "SELECT prijavavlaka.id, prijavavlaka.vlak_id AS 'id_vlaka', v_id.naziv AS 'naziv_vlaka', "
                . "k_id.id AS 'id_korisnika', k_id.ime, k_id.prezime, k_id.korisnicko_ime AS 'korime', "
                . "t_id.id AS 'id_tematike', t_id.naziv as 'naziv_tematike', prijavavlaka.status_id AS 'id_statusa', "
                . "s_id.status, i_id.datum_pocetka, m_id.vazi_do "
                . "FROM prijavavlaka "
                . "INNER JOIN vlak v_id ON prijavavlaka.vlak_id = v_id.id "
                . "INNER JOIN korisnik k_id ON k_id.id = v_id.vlasnik_id "
                . "INNER JOIN izlozba i_id ON prijavavlaka.izlozba_id = i_id.id "
                . "INNER JOIN tematika t_id ON i_id.tematika_id = t_id.id "
                . "INNER JOIN moderatori m_id ON m_id.tematika_id = t_id.id "
                . "INNER JOIN korisnik kor_id ON m_id.moderator_id = kor_id.id "
                . "INNER JOIN statusprijave s_id ON s_id.id = prijavavlaka.status_id "
                . "WHERE kor_id.korisnicko_ime = '{$korisnicko_ime}' AND m_id.vazi_od <= '{$this->_virtualno_vrijeme}'";
        $rezultat_sqla_informacija_moderatora = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_prijava_vlaka_administrator($rezultat_sqla_informacija_moderatora);
    }

    public function provjeri_broj_korisnika_na_izlozbi($id_prijave_vlaka) {
        $sql = "SELECT SUM(pv.status_id=1) AS 'trenutni', i.broj_korisnika"
                . " FROM prijavavlaka pv"
                . " INNER JOIN izlozba i ON i.id = pv.izlozba_id"
                . " WHERE i.id IN ( SELECT izlozba_id FROM prijavavlaka WHERE prijavavlaka.id = {$id_prijave_vlaka})";
        $rezultat_sqla_broj_korisnika_izlozbe = $this->_db->dohvati_podatke($sql);

        $prijava_vlaka = new Prijava_vlaka;

        $prijava_vlaka->trenutni_broj_korisnika = $rezultat_sqla_broj_korisnika_izlozbe[0]["trenutni"];
        $prijava_vlaka->broj_korisnika = $rezultat_sqla_broj_korisnika_izlozbe[0]["broj_korisnika"];

        if ($prijava_vlaka->trenutni_broj_korisnika >= $prijava_vlaka->broj_korisnika) {
            throw new Exception("Ne možete više prihvaćivati prijave kod ove izložbe!");
        } else {
            return TRUE;
        }
    }

    public function dohvati_prijavljene_korisnike_sa_izlozbe($id_izlozbe) {
        $sql = "SELECT v.id, v.naziv AS 'naziv_vlaka', v.max_brzina, v.broj_sjedala, vp.naziv_pogona, k.id AS 'id_korisnika', k.ime, k.prezime, k.korisnicko_ime, "
                . "pv.id AS 'id_prijave_vlaka', i.datum_pocetka, getStatusIzlozbe(i.id, '{$this->_virtualno_vrijeme}') AS 'status', "
                . "( SELECT m.url FROM materijal m WHERE m.prijava_vlaka_id = pv.id AND m.vrsta_materijala_id = 1 ORDER BY id DESC LIMIT 1) AS 'url_slike' "
                . "FROM prijavavlaka pv "
                . "INNER JOIN vlak v ON pv.vlak_id=v.id "
                . "INNER JOIN vrstapogona vp ON v.vrsta_pogona_id = vp.id "
                . "INNER JOIN korisnik k ON v.vlasnik_id = k.id "
                . "INNER JOIN statusprijave sp ON pv.status_id = sp.id "
                . "INNER JOIN izlozba i ON pv.izlozba_id = i.id "
                . "INNER JOIN glasovanje gl ON gl.izlozba_id = i.id "
                . "WHERE pv.izlozba_id = {$id_izlozbe} AND pv.status_id = 1";
        $rezultat_sqla_prijavljene_korisnika_izlozbe = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_prijavljenih_korisnika($rezultat_sqla_prijavljene_korisnika_izlozbe);
    }

    private function _kreiraj_model_prijavljenih_korisnika($rezultat_sqla_prijavljene_korisnika_izlozbe) {
        $popis_prijavljenih_korisnika = array();
        foreach ($rezultat_sqla_prijavljene_korisnika_izlozbe as $model_sqla) {
            $prijava_vlaka = new Prijava_vlaka;
            
            $url_slike = $model_sqla["url_slike"] ? $model_sqla["url_slike"] : Postavke::dohvati_server_url() . "multimedija/vlak1.jpg";

            $prijava_vlaka->id = $model_sqla["id_prijave_vlaka"];
            $prijava_vlaka->id_vlaka = $model_sqla["id"];
            $prijava_vlaka->naziv = $model_sqla["naziv_vlaka"];
            $prijava_vlaka->max_brzina = $model_sqla["max_brzina"];
            $prijava_vlaka->broj_sjedala = $model_sqla["broj_sjedala"];
            $prijava_vlaka->naziv_pogona = $model_sqla["naziv_pogona"];
            $prijava_vlaka->id_korisnika = $model_sqla["id_korisnika"];
            $prijava_vlaka->ime_korisnika = $model_sqla["ime"];
            $prijava_vlaka->prezime_korisnika = $model_sqla["prezime"];
            $prijava_vlaka->korisnicko_ime = $model_sqla["korisnicko_ime"];
            $prijava_vlaka->status = $model_sqla["status"];
            $prijava_vlaka->datum_pocetka_izlozbe = $model_sqla["datum_pocetka"];
            $prijava_vlaka->url_slike = $url_slike;

            array_push($popis_prijavljenih_korisnika, $prijava_vlaka);
        }
        return $popis_prijavljenih_korisnika;
    }

    public function dohvati_detalje_prijavljenog_vlaka($id_prijave_vlaka) {
        $sql = "SELECT v.naziv, v.max_brzina, v.broj_sjedala, v.opis AS 'opis_vlaka', k.ime, k.prezime, k.korisnicko_ime, "
                . "k.email, vp.naziv_pogona, vp.opis AS 'opis_pogona' "
                . "FROM prijavavlaka pv "
                . "INNER JOIN vlak v ON pv.vlak_id = v.id "
                . "INNER JOIN korisnik k ON v.vlasnik_id = k.id "
                . "INNER JOIN vrstapogona vp ON v.vrsta_pogona_id = vp.id "
                . "WHERE pv.id = {$id_prijave_vlaka}";
        $rezultat_sqla_detalja_prijavljenog_vlaka = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_detalja_prijavljenog_vlaka($rezultat_sqla_detalja_prijavljenog_vlaka[0]);
    }

    private function _kreiraj_model_detalja_prijavljenog_vlaka($rezultat_sqla_detalja_prijavljenog_vlaka) {
        $prijava_vlaka = new Prijava_vlaka;

        $prijava_vlaka->naziv = $rezultat_sqla_detalja_prijavljenog_vlaka["naziv"];
        $prijava_vlaka->max_brzina = $rezultat_sqla_detalja_prijavljenog_vlaka["max_brzina"];
        $prijava_vlaka->broj_sjedala = $rezultat_sqla_detalja_prijavljenog_vlaka["broj_sjedala"];
        $prijava_vlaka->opis = $rezultat_sqla_detalja_prijavljenog_vlaka["opis_vlaka"];
        $prijava_vlaka->naziv_pogona = $rezultat_sqla_detalja_prijavljenog_vlaka["naziv_pogona"];
        $prijava_vlaka->opis_pogona = $rezultat_sqla_detalja_prijavljenog_vlaka["opis_pogona"];
        $prijava_vlaka->ime_korisnika = $rezultat_sqla_detalja_prijavljenog_vlaka["ime"];
        $prijava_vlaka->prezime_korisnika = $rezultat_sqla_detalja_prijavljenog_vlaka["prezime"];
        $prijava_vlaka->korisnicko_ime = $rezultat_sqla_detalja_prijavljenog_vlaka["korisnicko_ime"];
        $prijava_vlaka->email = $rezultat_sqla_detalja_prijavljenog_vlaka["korisnicko_ime"];

        return $prijava_vlaka;
    }

    public function dohvati_id_izlozbe($id_prijava_vlaka) {
        $sql = "SELECT izlozba_id FROM prijavavlaka WHERE id = {$id_prijava_vlaka}";
        $rezultat_sqla_id_izlozbe = $this->_db->dohvati_podatke($sql);

        $prijava_vlaka = new Prijava_vlaka;
        $prijava_vlaka->izlozba_id = $rezultat_sqla_id_izlozbe[0]["izlozba_id"];

        return $prijava_vlaka->izlozba_id;
    }

    public function dohvati_detalje_prijavljenih_korisnika_kod_zavrsenih_izlozbi($id_izlozbe) {
        $sql = "SELECT k.ime, k.prezime, k.korisnicko_ime, k.email, v.naziv AS 'naziv_vlaka', t.naziv AS 'naziv_tematike'"
                . " FROM prijavavlaka pv"
                . " INNER JOIN vlak v ON v.id = pv.vlak_id"
                . " INNER JOIN korisnik k ON k.id = v.vlasnik_id"
                . " INNER JOIN izlozba i ON i.id = pv.izlozba_id"
                . " INNER JOIN tematika t ON t.id = i.tematika_id"
                . " WHERE pv.status_id = 1 AND pv.izlozba_id = {$id_izlozbe}"
                . " ORDER BY pv.id DESC LIMIT 10";
        $rezultat_sqla_detalja_prijavljenih_korisnika_zavrsenih_izlozbi = $this->_db->dohvati_podatke($sql);


        return $this->_kreiraj_model_detalja_prijavljenih_korisnika_zavrsenih_izlozbi($rezultat_sqla_detalja_prijavljenih_korisnika_zavrsenih_izlozbi);
    }

    private function _kreiraj_model_detalja_prijavljenih_korisnika_zavrsenih_izlozbi($rezultat_sqla_detalja_prijavljenih_korisnika_zavrsenih_izlozbi) {
        $popis_prijavljenih_korisnika = array();

        foreach ($rezultat_sqla_detalja_prijavljenih_korisnika_zavrsenih_izlozbi as $model_sqla) {
            $prijava_vlaka = new Prijava_vlaka;

            $prijava_vlaka->ime_korisnika = $model_sqla["ime"];
            $prijava_vlaka->prezime_korisnika = $model_sqla["prezime"];
            $prijava_vlaka->korisnicko_ime = $model_sqla["korisnicko_ime"];
            $prijava_vlaka->email = $model_sqla["email"];
            $prijava_vlaka->naziv = $model_sqla["naziv_vlaka"];
            $prijava_vlaka->naziv_tematike = $model_sqla["naziv_tematike"];

            array_push($popis_prijavljenih_korisnika, $prijava_vlaka);
        }
        return $popis_prijavljenih_korisnika;
    }

    public function zapisi_podatke_iz_sigurosne_kopije($zapis_prijave_vlakova) {
        $sql = "INSERT INTO prijavavlaka VALUES " . $zapis_prijave_vlakova;
        $this->_db->zapisi_podatke($sql);
        $this->_dnevnik_repo->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
    }

}
