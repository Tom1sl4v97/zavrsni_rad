<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Dnevnik_db_repozitorij
 *
 * @author franj
 */
require_once(ROOT . 'repositories/interfaces/Dnevnik_repozitorij_interface.php');
require_once(ROOT . 'repositories/database/Baza.php');
require_once(ROOT . 'models/Dnevnik.php');
require_once(ROOT . 'utils/Postavke.php');
require_once(ROOT . 'repositories/Korisnici_db_repozitorij.php');

class Dnevnik_db_repozitorij implements Dnevnik_repozitorij_interface {

    private $_db;
    private static $_virtualno_vrijeme;
    private $_korisnici_repo;

    public function __construct() {
        self::$_virtualno_vrijeme = Postavke::dohvati_virtualno_vrijeme();

        $this->_db = Baza::dohvati_instancu();
        $this->_korisnici_repo = new Korisnici_db_repozitorij();
    }

    public function dohvati_listu($datum_pretrage = "") {
        $sql = "SELECT d.id, d.stranica, d.upit, d.datum_pristupa, td.opis, k.ime, k.prezime, k.korisnicko_ime, k.email "
                . "FROM dnevnik d "
                . "INNER JOIN korisnik k ON d.korisnik_id = k.id "
                . "INNER JOIN tipdnevnika td ON td.id = d.tip_dnevnika_id "
                . "WHERE d.datum_pristupa >= '2021-01-01'";

        if (!empty($datum_pretrage)) {
            if (!empty($datum_pretrage["pocetniDatum"])) {
                $sql .= " AND d.datum_pristupa >= '{$datum_pretrage["pocetniDatum"]}'";
            }
            if (!empty($datum_pretrage["zavrsniDatum"])) {
                $sql .= " AND d.datum_pristupa <= '{$datum_pretrage["zavrsniDatum"]}'";
            }
        }
        $sql .= " ORDER BY tip_dnevnika_id";

        $rezultat_sqla_dnevnika = $this->_db->dohvati_podatke($sql);
        $lista_dnevnika = $this->_kreiraj_model_dnevnika($rezultat_sqla_dnevnika);

        return $lista_dnevnika;
    }

    private function _kreiraj_model_dnevnika($rezultat_sqla_dnevnika) {
        $lista_dnevnika = array();

        foreach ($rezultat_sqla_dnevnika as $model_sqla) {
            $dnevnik = new Dnevnik;

            $dnevnik->id = $model_sqla["id"];
            $dnevnik->stranica = $model_sqla["stranica"];
            $dnevnik->upit = $model_sqla["upit"];
            $dnevnik->datum_pristupa = $model_sqla["datum_pristupa"];
            $dnevnik->tip_dnevnika_opis = $model_sqla["opis"];
            $dnevnik->ime = $model_sqla["ime"];
            $dnevnik->prezime = $model_sqla["prezime"];
            $dnevnik->korisnicko_ime = $model_sqla["korisnicko_ime"];
            $dnevnik->email = $model_sqla["email"];

            array_push($lista_dnevnika, $dnevnik);
        }
        return $lista_dnevnika;
    }

    public function kreiraj_dnevnik($korisnicko_ime, $tip_dnevnika, $upit = "") {
        if (($tip_dnevnika == Tip_dnevnika::OSTALE_RADNJE OR $tip_dnevnika == Tip_dnevnika::PRIJAVA_ODJAVA) AND!empty($upit)) {
            throw new Exception('Unesen neispravan tip dnevnika. Za unos sql upita koristiti tip dnevnika 3-"Rad s bazom".');
        }
        if ($tip_dnevnika == Tip_dnevnika::RAD_S_BAZOM AND empty($upit)) {
            throw new Exception('Potrebno je unijeti sql upit ukoliko se koristiti tip dnevnika 3-"Rad s bazom".');
        }

        $podaci_korisnika = $this->_korisnici_repo->dohvati_prema_korisnicko_ime($korisnicko_ime);

        $sql = "INSERT INTO dnevnik (stranica, upit, datum_pristupa, tip_dnevnika_id, korisnik_id)"
                . " VALUES ('{$_SERVER['REQUEST_URI']}', " . '"' . $upit . '"' . ", '" . self::$_virtualno_vrijeme . "', " . $tip_dnevnika . ", {$podaci_korisnika->id})";

        $this->_db->zapisi_podatke($sql);
    }

}
