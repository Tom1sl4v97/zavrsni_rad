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
include_once(ROOT . 'repositories/interfaces/Moderatori_repozitorij_interface.php');
include_once(ROOT . 'repositories/Dnevnik_db_repozitorij.php');
include_once(ROOT . 'models/Moderator.php');
include_once(ROOT . 'models/Dnevnik.php');
include_once(ROOT . 'repositories/database/Baza.php');
include_once(ROOT . 'utils/Postavke.php');

class Moderatori_db_repozitorij implements Moderatori_repozitorij_interface {

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
        $moderator_tematike = new Moderator;
        $sql = "SELECT * FROM moderatori WHERE id = {$id}";
        $rezultat_sqla_tablice_moderatora = $this->_db->dohvati_podatke($sql);

        $moderator_tematike->id = $rezultat_sqla_tablice_moderatora[0]["id"];
        $moderator_tematike->administrator_id = $rezultat_sqla_tablice_moderatora[0]["administrator_id"];
        $moderator_tematike->moderator_id = $rezultat_sqla_tablice_moderatora[0]["moderator_id"];
        $moderator_tematike->tematika_id = $rezultat_sqla_tablice_moderatora[0]["tematika_id"];
        $moderator_tematike->vazi_od = $rezultat_sqla_tablice_moderatora[0]["vazi_od"];
        $moderator_tematike->vazi_do = $rezultat_sqla_tablice_moderatora[0]["vazi_do"];

        return $moderator_tematike;
    }

    public function kreiraj($uneseni_podaci) {
        $moderator = new Moderator;

        $moderator->administrator_id = $uneseni_podaci["id_administratora"];
        $moderator->moderator_id = $uneseni_podaci["odabirModeratoraTematike"];
        $moderator->tematika_id = $uneseni_podaci["odabirTematike"];
        $moderator->vazi_od = $uneseni_podaci["datumOd"];
        $moderator->vazi_do = $uneseni_podaci["datumDo"];

        $sql = "INSERT INTO moderatori (administrator_id, moderator_id, tematika_id, vazi_od, vazi_do) VALUES "
                . "({$moderator->administrator_id},{$moderator->moderator_id},{$moderator->tematika_id},"
                . "'{$moderator->vazi_od}', '{$moderator->vazi_do}')";

        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        if (!$this->_db->zapisi_podatke($sql)) {
            throw new Exception("Moderator je već dodijeljen navedenoj tematici.");
        }
    }

    public function dohvati_listu() {
        throw new Exception('Onemogućena funkcionalnost.');
    }

    public function obrisi($id_zapisa) {
        $sql = "DELETE FROM moderatori WHERE id = {$id_zapisa}";
        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function azuriraj($uneseni_podaci) {
        $moderator = new Moderator;

        $moderator->id = $uneseni_podaci["id_tablice_moderatora"];
        $moderator->administrator_id = $uneseni_podaci["id_administratora"];
        $moderator->moderator_id = $uneseni_podaci["odabirModeratoraTematike"];
        $moderator->tematika_id = $uneseni_podaci["odabirTematike"];
        $moderator->vazi_od = $uneseni_podaci["datumOd"];
        $moderator->vazi_do = $uneseni_podaci["datumDo"];

        $sql = "UPDATE moderatori SET "
                . "administrator_id = {$moderator->administrator_id},"
                . "moderator_id = {$moderator->moderator_id},"
                . "tematika_id = {$moderator->tematika_id},"
                . "vazi_od = '{$moderator->vazi_od}',"
                . "vazi_do = '{$moderator->vazi_do}' "
                . "WHERE id = {$moderator->id}";

        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function dohvati_popis_moderatora() {
        $sql = "SELECT moderatori.id, a_id.korisnicko_ime AS 'korisnicko_ime_administratora', "
                . "m_id.korisnicko_ime AS 'korisnicko_ime_moderatora', t_id.naziv AS 'naziv_tematike', "
                . "moderatori.vazi_od, moderatori.vazi_do FROM moderatori "
                . "INNER JOIN korisnik a_id ON moderatori.administrator_id = a_id.id "
                . "INNER JOIN korisnik m_id ON moderatori.moderator_id = m_id.id "
                . "INNER JOIN tematika t_id ON moderatori.tematika_id = t_id.id "
                . "ORDER BY m_id.korisnicko_ime, t_id.naziv";
        $rezultat_sqla_popisa_moderatora = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_popisa_moderatora($rezultat_sqla_popisa_moderatora);
    }

    private function _kreiraj_model_popisa_moderatora($rezultat_sqla_popisa_moderatora) {
        $popis_moderatora = array();
        foreach ($rezultat_sqla_popisa_moderatora as $model_sqla) {
            $moderator = new Moderator;

            $moderator->id = $model_sqla["id"];
            $moderator->korisnicko_ime_administratora = $model_sqla["korisnicko_ime_administratora"];
            $moderator->korisnicko_ime_moderatora = $model_sqla["korisnicko_ime_moderatora"];
            $moderator->naziv_tematike = $model_sqla["naziv_tematike"];
            $moderator->vazi_od = $model_sqla["vazi_od"];
            $moderator->vazi_do = $model_sqla["vazi_do"];

            array_push($popis_moderatora, $moderator);
        }
        return $popis_moderatora;
    }

    public function dohvati_popis_tematike_administratora() {
        $popis_svih_tematike = array();
        $sql = "SELECT * FROM tematika";

        $rezultat_sqla_tematike_administratora = $this->_db->dohvati_podatke($sql);

        foreach ($rezultat_sqla_tematike_administratora as $model_sqla) {
            $moderator = new Moderator;

            $moderator->tematika_id = $model_sqla["id"];
            $moderator->naziv_tematike = $model_sqla["naziv"];
            $moderator->opis_tematike = $model_sqla["opis"];
            $moderator->vazi_do = Postavke::dohvati_virtualno_vrijeme();

            array_push($popis_svih_tematike, $moderator);
        }
        return $popis_svih_tematike;
    }

    public function dohvati_popis_tematike_moderatora($korisnicko_ime) {
        $sql = "SELECT moderatori.moderator_id AS 'id_moderatora' ,m_id.korisnicko_ime AS 'moderator',"
                . "moderatori.tematika_id AS 'id_tematike', t_id.naziv, t_id.opis AS 'opis', "
                . "moderatori.vazi_od, moderatori.vazi_do FROM moderatori "
                . "INNER JOIN korisnik m_id ON moderatori.moderator_id = m_id.id "
                . "INNER JOIN tematika t_id ON moderatori.tematika_id = t_id.id "
                . "WHERE m_id.korisnicko_ime = '{$korisnicko_ime}' AND moderatori.vazi_od <= '{$this->_virtualno_vrijeme}'";
        $rezultat_sqla_tematike_moderatora = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_tematike_moderatora($rezultat_sqla_tematike_moderatora);
    }

    private function _kreiraj_model_tematike_moderatora($rezultat_sqla_tematike_moderatora) {
        $popis_tematike_moderatora = array();
        foreach ($rezultat_sqla_tematike_moderatora as $model_sqla) {
            $moderator = new Moderator;

            $moderator->id = $model_sqla["id_moderatora"];
            $moderator->korisnicko_ime_moderatora = $model_sqla["moderator"];
            $moderator->tematika_id = $model_sqla["id_tematike"];
            $moderator->naziv_tematike = $model_sqla["naziv"];
            $moderator->opis_tematike = $model_sqla["opis"];
            $moderator->vazi_od = $model_sqla["vazi_od"];
            $moderator->vazi_do = $model_sqla["vazi_do"];

            if ($moderator->vazi_do != null AND $moderator->vazi_do <= $this->_virtualno_vrijeme){
                CONTINUE;
            }
            
            array_push($popis_tematike_moderatora, $moderator);
        }
        
        return $popis_tematike_moderatora;
    }

}
