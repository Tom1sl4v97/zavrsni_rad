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
include_once(ROOT . 'repositories/interfaces/Tematika_repozitorij_interface.php');
include_once(ROOT . 'repositories/Dnevnik_db_repozitorij.php');
include_once(ROOT . 'repositories/database/Baza.php');
include_once(ROOT . 'models/Tematika.php');
include_once(ROOT . 'models/Dnevnik.php');
include_once(ROOT . 'utils/Postavke.php');

class Tematika_db_repozitorij implements Tematika_repozitorij_interface {

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
        $sql = "SELECT * FROM tematika WHERE id = {$id}";
        $rezultat_sqla_tematike = $this->_db->dohvati_podatke($sql);
        if (empty($rezultat_sqla_tematike)) {
            throw new Exception("Nije pronađena tematika sa ID-jem: {$id}");
        }
        return $this->_kreiraj_model_tematike($rezultat_sqla_tematike[0]);
    }

    private function _kreiraj_model_tematike($rezultat_sqla_tematike) {
        $tematika = new Tematika();

        $tematika->id = $rezultat_sqla_tematike["id"];
        $tematika->naziv = $rezultat_sqla_tematike["naziv"];
        $tematika->opis = $rezultat_sqla_tematike["opis"];
        $tematika->kreirao_korisnik_id = $rezultat_sqla_tematike["kreirao_korisnik_id"];
        $tematika->datum_kreiranja = $rezultat_sqla_tematike["datum_kreiranja"];
        $tematika->azurirao_korisnik_id = $rezultat_sqla_tematike["azurirao_korisnik_id"];
        $tematika->datum_azuriranja = $rezultat_sqla_tematike["datum_azuriranja"];

        return $tematika;
    }

    public function kreiraj($uneseni_podaci) {
        $tematika = new Tematika;

        $tematika->naziv = $uneseni_podaci["nazivTematike"];
        $tematika->opis = $uneseni_podaci["opisTematike"];
        $tematika->kreirao_korisnik_id = $uneseni_podaci["id_administratora"];

        $sql = "INSERT INTO tematika (naziv, opis, kreirao_korisnik_id, datum_kreiranja) VALUES "
                . "('{$tematika->naziv}','{$tematika->opis}',{$tematika->kreirao_korisnik_id},'{$this->_virtualno_vrijeme}')";

        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function dohvati_listu() {
        $popis_svih_tematike = array();
        $sql = "SELECT * FROM tematika";
        $rezultat_sqla_temaatike = $this->_db->dohvati_podatke($sql);

        if (empty($rezultat_sqla_temaatike)) {
            throw new Exception("Nije pronađena niti jedna tematika vlakova");
        }
        if (!empty($rezultat_sqla_temaatike)) {
            foreach ($rezultat_sqla_temaatike as $model_sqla) {
                array_push($popis_svih_tematike, $this->_kreiraj_model_tematike($model_sqla));
            }
            return $popis_svih_tematike;
        }
    }

    public function obrisi($id_zapisa) {
        $sql = "DELETE FROM tematika WHERE id = {$id_zapisa}";
        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function azuriraj($uneseni_podaci) {
        $tematika = new Tematika;

        $tematika->id = $uneseni_podaci["id_tematike"];
        $tematika->naziv = $uneseni_podaci["nazivTematike"];
        $tematika->opis = $uneseni_podaci["opisTematike"];
        $tematika->azurirao_korisnik_id = $uneseni_podaci["id_administratora"];
        $tematika->datum_azuriranja = Postavke::dohvati_virtualno_vrijeme();

        $sql = "UPDATE tematika SET"
                . " naziv = '{$tematika->naziv}',"
                . " opis = '{$tematika->opis}',"
                . " azurirao_korisnik_id = {$tematika->azurirao_korisnik_id},"
                . " datum_azuriranja = '{$tematika->datum_azuriranja}'"
                . " WHERE id = {$tematika->id}";

        $this->_dnevnik->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::RAD_S_BAZOM, $sql);
        $this->_db->zapisi_podatke($sql);
    }

    public function dohvati_detalje_tematike_prema_id($id_tematike) {
        $sql = "SELECT tematika.id, tematika.naziv, tematika.opis, korisnikKreirao.korisnicko_ime as 'kreirao_korisnik', "
                . "tematika.datum_kreiranja, korisnikAzurirao.korisnicko_ime, tematika.datum_azuriranja "
                . "FROM tematika "
                . "INNER JOIN korisnik korisnikKreirao ON tematika.kreirao_korisnik_id = korisnikKreirao.id "
                . "LEFT JOIN korisnik korisnikAzurirao On tematika.azurirao_korisnik_id = korisnikAzurirao.id "
                . "WHERE tematika.id = {$id_tematike}";
        $rezultat_sqla_detalja_tematike_prema_id = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_detalje_tematike_prema_id($rezultat_sqla_detalja_tematike_prema_id[0]);
    }

    private function _kreiraj_model_detalje_tematike_prema_id($rezultat_sqla_detalja_tematike_prema_id) {
        $tematika = new Tematika;

        $tematika->id = $rezultat_sqla_detalja_tematike_prema_id["id"];
        $tematika->naziv = $rezultat_sqla_detalja_tematike_prema_id["naziv"];
        $tematika->opis = $rezultat_sqla_detalja_tematike_prema_id["opis"];
        $tematika->korisnik_kreiranja = $rezultat_sqla_detalja_tematike_prema_id["kreirao_korisnik"];
        $tematika->datum_kreiranja = $rezultat_sqla_detalja_tematike_prema_id["datum_kreiranja"];
        $tematika->korisnik_azuriranja = $rezultat_sqla_detalja_tematike_prema_id["korisnicko_ime"];
        $tematika->datum_azuriranja = $rezultat_sqla_detalja_tematike_prema_id["datum_azuriranja"];

        return $tematika;
    }

}
