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
include_once(ROOT . 'repositories/interfaces/Korisnici_repozitorij_interface.php');
include_once(ROOT . 'repositories/database/Baza.php');
include_once(ROOT . 'models/Korisnik.php');
include_once(ROOT . 'utils/Postavke.php');

class Korisnici_db_repozitorij implements Korisnici_repozitorij_interface {

    private $_db;
    private $_virtualno_vrijeme;

    public function __construct() {
        $this->_db = Baza::dohvati_instancu();
        $this->_virtualno_vrijeme = Postavke::dohvati_virtualno_vrijeme();
    }

    public function dohvati_prema_id($id) {
        $sql = "SELECT * FROM korisnik WHERE id = {$id}";
        $rezultat_sqla_korisnika = $this->_db->dohvati_podatke($sql);

        if (!empty($rezultat_sqla_korisnika)) {
            return $this->_kreiraj_model_korisnika($rezultat_sqla_korisnika[0]);
        }
        if (empty($rezultat_sqla_korisnika)) {
            throw new Exception("Nije pronađeni traženi korisnik");
        }
    }

    private function _kreiraj_model_korisnika($rezultat_sqla_korisnika) {
        $korisnik = new Korisnik;

        $korisnik->id = $rezultat_sqla_korisnika["id"];
        $korisnik->ime = $rezultat_sqla_korisnika["ime"];
        $korisnik->prezime = $rezultat_sqla_korisnika["prezime"];
        $korisnik->korisnicko_ime = $rezultat_sqla_korisnika["korisnicko_ime"];
        $korisnik->email = $rezultat_sqla_korisnika["email"];
        $korisnik->uvjeti_koristenja = $rezultat_sqla_korisnika["uvjeti_koristenja"];
        $korisnik->status = $rezultat_sqla_korisnika["status"];
        $korisnik->tip_korisnika_id = $rezultat_sqla_korisnika["tip_korisnika_id"];
        $korisnik->broj_neuspijesnih_prijava = $rezultat_sqla_korisnika["broj_neuspijesnih_prijava"];
        $korisnik->lozinka_sha1 = $rezultat_sqla_korisnika["lozinka_sha1"];
        $korisnik->salt = $rezultat_sqla_korisnika["salt"];
        $korisnik->datum_kreiranja = $rezultat_sqla_korisnika["datum_kreiranja"];

        return $korisnik;
    }

    public function kreiraj($uneseni_podaci) {
        $korisnik = $this->_kreiraj_model_korisnika_registracije($uneseni_podaci);
        
        $sql = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka_sha1, email, uvjeti_koristenja, status, tip_korisnika_id, lozinka, salt)" .
                " VALUES ('{$korisnik->ime}', '{$korisnik->prezime}', '{$korisnik->korisnicko_ime}', '{$korisnik->lozinka_sha1}', "
                . "'{$korisnik->email}', '{$korisnik->uvjeti_koristenja}', 0, '" . uloga_korisnika::PRIJAVLJENI_KORISNIK . "', "
                . "'{$korisnik->lozinka}', '{$korisnik->salt}')";

        $this->_db->zapisi_podatke($sql);
    }
    
    private function _kreiraj_model_korisnika_registracije($uneseni_podaci){
        $korisnik = new Korisnik;
        
        if (!empty($uneseni_podaci["ime"])) {
            $korisnik->ime = $uneseni_podaci["ime"];
        }
        if (!empty($uneseni_podaci["prezime"])) {
            $korisnik->prezime = $uneseni_podaci["prezime"];
        }
        $korisnik->korisnicko_ime = $uneseni_podaci["korisnicko_ime"];
        $korisnik->lozinka = $uneseni_podaci["lozinka"];
        $korisnik->email = $uneseni_podaci["email"];
        $korisnik->salt = "";
        $korisnik->lozinka_sha1 = hash("sha256", $korisnik->lozinka . $korisnik->salt);
        $korisnik->uvjeti_koristenja = $this->_virtualno_vrijeme;
        
        return $korisnik;
    }

    public function dohvati_listu() {
        $sql = "SELECT k.*, tk.naziv AS 'naziv_uloge' FROM korisnik k "
                . "INNER JOIN tipkorisnika tk ON k.tip_korisnika_id = tk.id";
        $rezultati_sqla_korisnika = $this->_db->dohvati_podatke($sql);

        return $this->_kreiraj_model_liste_korisnika($rezultati_sqla_korisnika);
    }

    private function _kreiraj_model_liste_korisnika($rezultati_sqla_korisnika) {
        $lista_korisnika = array();
        foreach ($rezultati_sqla_korisnika as $model_sqla) {
            $korisnik = new Korisnik;

            $korisnik->id = $model_sqla["id"];
            $korisnik->ime = $model_sqla["ime"];
            $korisnik->prezime = $model_sqla["prezime"];
            $korisnik->korisnicko_ime = $model_sqla["korisnicko_ime"];
            $korisnik->lozinka_sha1 = $model_sqla["lozinka_sha1"];
            $korisnik->email = $model_sqla["email"];
            $korisnik->uvjeti_koristenja = $model_sqla["uvjeti_koristenja"];
            $korisnik->status = $model_sqla["status"];
            $korisnik->tip_korisnika_id = $model_sqla["tip_korisnika_id"];
            $korisnik->broj_neuspijesnih_prijava = $model_sqla["broj_neuspijesnih_prijava"];
            $korisnik->salt = $model_sqla["salt"];
            $korisnik->datum_kreiranja = $model_sqla["datum_kreiranja"];
            $korisnik->naziv_uloge = $model_sqla["naziv_uloge"];

            array_push($lista_korisnika, $korisnik);
        }
        return $lista_korisnika;
    }

    public function obrisi($id_zapisa) {
        $sql = "DELETE FROM korisnik WHERE korisnicko_ime = '{$id_zapisa}'";
        $this->_db->zapisi_podatke($sql);
    }

    public function azuriraj($uneseni_podaci) {
        $sql = "UPDATE korisnik SET "
                . "broj_neuspijesnih_prijava = {$uneseni_podaci->broj_neuspijesnih_prijava} "
                . "WHERE korisnicko_ime = '{$uneseni_podaci->korisnicko_ime}'";
        $this->_db->zapisi_podatke($sql);
    }

    public function dohvati_prema_korisnicko_ime($korisnicko_ime) {
        $sql = "SELECT * FROM korisnik WHERE korisnicko_ime = '{$korisnicko_ime}'";
        $rezultat_sqla_korisnika = $this->_db->dohvati_podatke($sql);
        if (!empty($rezultat_sqla_korisnika)) {
            return $this->_kreiraj_model_korisnika($rezultat_sqla_korisnika[0]);
        } else {
            return FALSE;
        }
    }

    public function dohvati_moderatore() {
        $moderatori = array();

        $sql = "SELECT id, ime, prezime, korisnicko_ime FROM korisnik WHERE tip_korisnika_id = " . uloga_korisnika::MODERATOR;
        $rezultat_sqla_korisnika = $this->_db->dohvati_podatke($sql);

        if (empty($rezultat_sqla_korisnika)) {
            throw new Exception("Nije pronađena niti jedna tematika vlakova");
        }

        foreach ($rezultat_sqla_korisnika as $model_sqla) {
            $moderator = new Korisnik;
            $moderator->id = $model_sqla["id"];
            $moderator->ime = $model_sqla["ime"];
            $moderator->prezime = $model_sqla["prezime"];
            $moderator->korisnicko_ime = $model_sqla["korisnicko_ime"];
            array_push($moderatori, $moderator);
        }
        return $moderatori;
    }

    public function aktiviraj_racun_korisnika($korisnik) {
        $sql = "UPDATE korisnik SET datum_kreiranja = '{$this->_virtualno_vrijeme}' WHERE korisnicko_ime = '{$korisnik->korisnicko_ime}'";
        $this->_db->zapisi_podatke($sql);
    }

    public function resetiraj_uvjete_koristenja() {
        $sql = "UPDATE korisnik SET status = 0 WHERE tip_korisnika_id != 1";
        $this->_db->zapisi_podatke($sql);
    }

    public function prihvacivanje_uvjeta_koristenja($id_korisnika) {
        $sql = "UPDATE korisnik SET status = 1 WHERE id = {$id_korisnika}";
        $this->_db->zapisi_podatke($sql);
    }

}
