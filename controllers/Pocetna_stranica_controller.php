<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pocetna_stranica_controller
 *
 * @author franj
 */
include_once('Controller.php');
include_once(ROOT . 'repositories/Korisnici_db_repozitorij.php');
include_once(ROOT . 'repositories/Izlozba_db_repozitorij.php');
include_once(ROOT . 'repositories/Prijava_vlaka_db_repozitorij.php');

class Pocetna_stranica_controller extends Controller {

    private $_korisnici_repo;
    private $_izlozba_repo;
    private $_prijava_vlaka_repo;
    private $_virtualno_vrijeme;
    private $_trajanje_aktivacijskog_linka;
    private $_korisnicko_ime;

    public function __construct() {
        parent::__construct();
        $this->_korisnici_repo = new Korisnici_db_repozitorij();
        $this->_izlozba_repo = new Izlozba_db_repozitorij();
        $this->_prijava_vlaka_repo = new Prijava_vlaka_db_repozitorij();
        $this->_virtualno_vrijeme = Postavke::dohvati_virtualno_vrijeme();
        $this->_korisnicko_ime = Sesija::dohvati_korisnicko_ime();
        $this->_trajanje_aktivacijskog_linka = '14'; //izraz je zapisan u satima.
    }

    function index($id_izlozbe = "") {
        $podaci_fronta["naslov_stranice"] = "Početna stranica";
        $podaci_fronta["opis_stranice"] = "Ovo je početna stranica, koja prikazije tablicu o pticama, kreirana 18.3.2021.";
        $podaci_fronta["izbor_slike"] = array("Moderni" => "Moderni vlak", "Brzi" => "Najbrži vlak", "Motorni" => "Motorni vlak",
            "Najmanji" => "Najmanji vlak", "Ogromni" => "Najveći vlak", "Parni" => "Lokomotive");
        $podaci_fronta["izlozba"] = $this->_izlozba_repo->dohvati_zavrsene_izlozbe();
        $podaci_fronta["podaci_glasanja"] = $this->_dohvati_rezultat_izlozbe($podaci_fronta["izlozba"]);

        if (!empty($id_izlozbe)) {
            $podaci_fronta["detalji_izlozbe"] = $this->_prijava_vlaka_repo->dohvati_detalje_prijavljenih_korisnika_kod_zavrsenih_izlozbi($id_izlozbe);
        }

        if (isset($this->_korisnicko_ime)) {
            $podaci_fronta["podaci_korisnika"] = $this->_provjeri_uvjete_koristenja();
        }

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("pocetna_stranica");
    }

    private function _dohvati_rezultat_izlozbe($izlozba) {
        for ($i = 0; $i < count($izlozba); $i++) {
            $odgovor = $this->_izlozba_repo->dohvati_pobjednika_izlozbe($izlozba[$i]->id);
            if ($odgovor != NULL) {
                $podaci_glasanja[$i] = $odgovor;
            } else {
                $podaci_glasanja[$i] = NULL;
            }
        }
        return $podaci_glasanja;
    }

    private function _provjeri_uvjete_koristenja() {
        $podaci_korisnika = $this->_korisnici_repo->dohvati_prema_korisnicko_ime($this->_korisnicko_ime);

        if ($this->_provjera_statusa_korisnika_i_postavljenog_kolacica($podaci_korisnika)) {
            $this->_kreiraj_kolacic_o_korisniku();
            return $podaci_korisnika;
        }
        return NULL;
    }

    private function _provjera_statusa_korisnika_i_postavljenog_kolacica($podaci_korisnika) {
        $status_prihvacenost = 0;
        return $podaci_korisnika->status == $status_prihvacenost AND!isset($_COOKIE["uvjeti_koristenja"]);
    }

    private function _kreiraj_kolacic_o_korisniku() {
        $trajanje_kolacica = Postavke::dohvati_txt_zapis_dokumenta("trajanje_kolacica");
        $vrijeme = strtotime($this->_virtualno_vrijeme) + ($trajanje_kolacica * 24 * 60 * 60);
        setcookie("uvjeti_koristenja", $this->_korisnicko_ime, $vrijeme, "/");
    }

    function autor_stranice() {
        $podaci_fronta["naslov_stranice"] = "Autor stranice";
        $podaci_fronta["opis_stranice"] = "Ovo je stranica o autoru, koja prikazije koja prikazuje osnovne informacije o autoru stranice. Kreirana 18.3.2021.";

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("autor");
    }

    function aktiviraj_korisnicki_racun($korisnicko_ime) {
        try {
            $korisnik = $this->_korisnici_repo->dohvati_prema_korisnicko_ime($korisnicko_ime);
            if (!$korisnik) {
                throw new Exception("Ne postoji korisnici račun, molimo vas kreirajte novi račun.");
            }
            if ($this->_provjeri_vrijeme_isticanja_lika($korisnik)) {
                $this->_korisnici_repo->obrisi($korisnik->korisnicko_ime);
                throw new Exception("Žao nam je aktivacijski link Vam je istekao!<br>Molimo Vas da si napravite "
                                . "novi korisnički račun i potvrdite aktivacijski link u roku od 14 sati.");
            } else {
                $this->_aktiviraj_korisnicki_racun($korisnik);
            }
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->index();
        }
    }

    private function _provjeri_vrijeme_isticanja_lika($korisnik) {
        $vrijeme_sa_aktivacijskim_linkom = date("d.m.Y. H:i:s", strtotime($korisnik->uvjeti_koristenja) + $this->_trajanje_aktivacijskog_linka * 60 * 60);
        $trenutno_virtualno_vrijeme = date("d.m.Y. H:i:s", strtotime($this->_virtualno_vrijeme));
        if ($vrijeme_sa_aktivacijskim_linkom <= $trenutno_virtualno_vrijeme) {
            return TRUE;
        }
        return FALSE;
    }

    private function _aktiviraj_korisnicki_racun($korisnik) {
        $this->_korisnici_repo->aktiviraj_racun_korisnika($korisnik);
        Sesija::kreiraj_korisnika(
                $korisnik->korisnicko_ime,
                $this->_virtualno_vrijeme,
                uloga_korisnika::PRIJAVLJENI_KORISNIK,
                Sesija::dohvati_dizajn_korisnika(),
                Sesija::dohvati_darkmode_korisnika()
        );
        $this->preusmjeri(Postavke::dohvati_server_url() . "pocetna_stranica/index/");
    }

}
