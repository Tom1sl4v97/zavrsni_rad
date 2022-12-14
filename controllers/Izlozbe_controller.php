<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Izlozbe_controller
 *
 * @author franj
 */
include_once('Controller.php');
include_once(ROOT . 'repositories/Korisnici_db_repozitorij.php');
include_once(ROOT . 'repositories/Tematika_db_repozitorij.php');
include_once(ROOT . 'repositories/Moderatori_db_repozitorij.php');
include_once(ROOT . 'repositories/Izlozba_db_repozitorij.php');
include_once(ROOT . 'repositories/Glasovanje_db_repozitorij.php');
include_once(ROOT . 'repositories/Vlak_db_repozitorij.php');
include_once(ROOT . 'repositories/Prijava_vlaka_db_repozitorij.php');
include_once(ROOT . 'repositories/Vrsta_materijala_db_repozitorij.php');
include_once(ROOT . 'repositories/Ocjena_db_repozitorij.php');

class Izlozbe_controller extends Controller {

    private $_korisnici_repo;
    private $_tematika_repo;
    private $_moderatori_repo;
    private $_izlozba_repo;
    private $_glasovanje_repo;
    private $_vlak_repo;
    private $_prijava_vlaka_repo;
    private $_vrsta_materijala_repo;
    private $_ocjena_repo;
    private $_korisnicko_ime;
    private $_uloga_prijavljenog_korisnika;

    public function __construct() {
        parent::__construct();
        $this->_korisnici_repo = new Korisnici_db_repozitorij();
        $this->_tematika_repo = new Tematika_db_repozitorij();
        $this->_moderatori_repo = new Moderatori_db_repozitorij();
        $this->_izlozba_repo = new Izlozba_db_repozitorij();
        $this->_glasovanje_repo = new Glasovanje_db_repozitorij();
        $this->_vlak_repo = new Vlak_db_repozitorij();
        $this->_prijava_vlaka_repo = new Prijava_vlaka_db_repozitorij();
        $this->_vrsta_materijala_repo = new Vrsta_materijala_db_repozitorij();
        $this->_ocjena_repo = new Ocjena_db_repozitorij();
        $this->_uloga_prijavljenog_korisnika = Sesija::dohvati_ulogu_korisnika();
        $this->_korisnicko_ime = Sesija::dohvati_korisnicko_ime();
    }

    function prikaz_administracija_izlozba($id_tematike = "") {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        $podaci_fronta["naslov_stranice"] = "Administracija izlozbi";
        $podaci_fronta["opis_stranice"] = "Stranica tematika vlakova administrator ima mogućnost dodijeliti novu ili "
                . "modificirati postojeću tematiku vlakova i dodijeliti im moderatore.";
        $podaci_fronta["lista_tematike_vlakova"] = $this->_tematika_repo->dohvati_listu();
        $podaci_fronta["popis_moderatora"] = $this->_moderatori_repo->dohvati_popis_moderatora();

        if (!empty($id_tematike)) {
            $podaci_fronta["detalji_tematike"] = $this->_tematika_repo->dohvati_detalje_tematike_prema_id($id_tematike);
        }

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("administracija_izlozba");
    }

    function azuriraj_tematiku($id_tematike) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        try {
            $popis_gresaka = array("nazivTematike" => "naziv tematike", "opisTematike" => "opis tematike");
            $uneseni_podaci = $this->provjeri_popunjesnost_obaveznih_podataka($popis_gresaka);

            if (!$uneseni_podaci) {
                return $this->prikaz_podataka_tematike($id_tematike);
            }

            $podaci_tematike = array_merge($uneseni_podaci, $this->_pripremi_podatke_azuriranja_tematike($id_tematike));
            $this->_odluka_o_zapisu_tematike($podaci_tematike);

            $this->preusmjeri(Postavke::dohvati_server_url() . "izlozbe/prikaz_administracija_izlozba/");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikaz_podataka_tematike($id_tematike);
        }
    }

    function prikaz_podataka_tematike($id_tematike = "") {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        $podaci_fronta["naslov_stranice"] = "Ažuriranje tematike vlakova";
        $podaci_fronta["opis_stranice"] = "Na ovoj stranici se dodaju nove ili uređuju postojeće tematike izložbi vlakova";
        $podaci_fronta["id"] = "";

        if (!empty($id_tematike)) {
            $tematika = $this->_tematika_repo->dohvati_prema_id($id_tematike);
            $podaci_fronta["naziv_tematike"] = $tematika->naziv;
            $podaci_fronta["opis_tematike"] = $tematika->opis;
            $podaci_fronta["id"] = $id_tematike;
        }


        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("azuriranje_tematike");
    }

    private function _pripremi_podatke_azuriranja_tematike($id_tematike) {
        $podaci_korisnika = $this->_korisnici_repo->dohvati_prema_korisnicko_ime($this->_korisnicko_ime);
        $podaci_azuriranja["id_administratora"] = $podaci_korisnika->id;
        $podaci_azuriranja["id_tematike"] = $id_tematike;

        return $podaci_azuriranja;
    }

    private function _odluka_o_zapisu_tematike($podaci_tematike) {
        if (empty($podaci_tematike["id_tematike"])) {
            $this->_tematika_repo->kreiraj($podaci_tematike);
        } else {
            $this->_tematika_repo->azuriraj($podaci_tematike);
        }
    }

    function obrisi_tematiku($id_tematike) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);
        $this->_tematika_repo->obrisi($id_tematike);
        $this->preusmjeri(Postavke::dohvati_server_url() . "izlozbe/prikaz_administracija_izlozba/");
    }

    function azuriraj_dodijelu_moderatora($id_teblice_moderatora) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        try {
            $popis_gresaka = array(
                "odabirTematike" => "tematiku vlakova",
                "odabirModeratoraTematike" => "moderatora tematike vlakova",
                "datumOd" => "od kada vrijedi moderator tematike", "datumDo"
            );
            $uneseni_podaci = $this->provjeri_popunjesnost_obaveznih_podataka($popis_gresaka);

            if (!$uneseni_podaci) {
                return $this->prikaz_dodijele_moderatora($id_teblice_moderatora);
            }

            $podaci_tablice_moderatora = array_merge($uneseni_podaci, $this->_pripremi_podatke_azuriranja_tablice_moderatora($id_teblice_moderatora));
            $this->_odluka_o_zapisu_tablice_moderatora($podaci_tablice_moderatora);

            $this->preusmjeri(Postavke::dohvati_server_url() . "izlozbe/prikaz_administracija_izlozba/");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikaz_dodijele_moderatora($id_teblice_moderatora);
        }
    }

    function prikaz_dodijele_moderatora($id_tablice_moderatora = "") {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        $podaci_fronta["naslov_stranice"] = "Dodijeli moderatora tematici vlakova";
        $podaci_fronta["opis_stranice"] = "Na ovoj stranici administrator dodijeljuje postojeću tematiku određenim moderatorima";
        $podaci_fronta["popis_moderatora"] = $this->_korisnici_repo->dohvati_moderatore();
        $podaci_fronta["popis_tematike_vlakova"] = $this->_tematika_repo->dohvati_listu();
        $podaci_fronta["id"] = "";

        if (!empty($id_tablice_moderatora)) {
            $popis_informacija_moderatora = $this->_moderatori_repo->dohvati_prema_id($id_tablice_moderatora);
            $popis_moderatora_tematike["id_moderator_tematike_vlakova"] = $popis_informacija_moderatora->moderator_id;
            $popis_moderatora_tematike["id_tematika_vlakova"] = $popis_informacija_moderatora->tematika_id;
            $popis_moderatora_tematike["vrijedi_od"] = date("Y-m-d", strtotime($popis_informacija_moderatora->vazi_od));
            $popis_moderatora_tematike["vrijedi_do"] = date("Y-m-d", strtotime($popis_informacija_moderatora->vazi_do));
            $podaci_fronta["podaci_uredivanja_moderatora_tematike"] = $popis_moderatora_tematike;
            $podaci_fronta["id"] = $id_tablice_moderatora;
        }

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("dodjela_moderatora");
    }

    private function _pripremi_podatke_azuriranja_tablice_moderatora($id_teblice_moderatora) {
        $podaci_korisnika = $this->_korisnici_repo->dohvati_prema_korisnicko_ime($this->_korisnicko_ime);
        $podaci_azuriranja["id_administratora"] = $podaci_korisnika->id;
        $podaci_azuriranja["id_tablice_moderatora"] = $id_teblice_moderatora;

        return $podaci_azuriranja;
    }

    private function _odluka_o_zapisu_tablice_moderatora($podaci_tablice_moderatora) {
        if (empty($podaci_tablice_moderatora["id_tablice_moderatora"])) {
            $this->_moderatori_repo->kreiraj($podaci_tablice_moderatora);
        }
        if (!empty($podaci_tablice_moderatora["id_tablice_moderatora"])) {
            $this->_moderatori_repo->azuriraj($podaci_tablice_moderatora);
        }
    }

    function obrisi_zapis_tablice_moderatora($id_tablice_moderatora) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        $this->_moderatori_repo->obrisi($id_tablice_moderatora);
        $this->preusmjeri(Postavke::dohvati_server_url() . "izlozbe/prikaz_administracija_izlozba/");
    }

    function prikaz_uredivanje_izlozba() {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::MODERATOR);

        $podaci_fronta["naslov_stranice"] = "Uređivanje izložba";
        $podaci_fronta["opis_stranice"] = "Ova stranica sadrži sve potrebne podatke za kreiranje i modificiranje dodijeljenih izlozbi prema moderatoru";

        $izbor_slika = array("Moderni" => "Moderni vlak", "Brzi" => "Najbrži vlak", "Motorni" => "Motorni vlak",
            "Najmanji" => "Najmanji vlak", "Ogromni" => "Najveći vlak", "Parni" => "Lokomotive");
        $podaci_fronta["izbor_slika"] = $izbor_slika;
        $podaci_fronta["virtualni_datum"] = Postavke::dohvati_virtualno_vrijeme();


        if ($this->_uloga_prijavljenog_korisnika == uloga_korisnika::ADMINISTRATOR) {
            $podaci_fronta["popis_tematike_vlakova"] = $this->_moderatori_repo->dohvati_popis_tematike_administratora();
            $podaci_fronta["popis_izlozbi"] = $this->_izlozba_repo->dohvati_listu();
        }
        if ($this->_uloga_prijavljenog_korisnika == uloga_korisnika::MODERATOR) {
            $podaci_fronta["popis_tematike_vlakova"] = $this->_moderatori_repo->dohvati_popis_tematike_moderatora($this->_korisnicko_ime);
            $podaci_fronta["popis_izlozbi"] = $this->_izlozba_repo->dohvati_listu_izlozbi_moderatora($this->_korisnicko_ime);
        }

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("uredivanje_izlozba");
    }

    function azuriraj_izlozbu($id_izlozbe) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::MODERATOR);

        try {
            $popis_gresaka = array(
                "odabirModeratoraTematike" => "temu izložbe", "datumPocetka" => "datum poćetka izložbe",
                "maxBrojKorisnika" => "maksimalan broj korisnika", "pocetakGlasovanja" => "pocetak glasovanja",
                "zavrsetakGlasovanja" => "zavrsetak glasovanja"
            );
            $uneseni_podaci = $this->provjeri_popunjesnost_obaveznih_podataka($popis_gresaka);

            if (!$uneseni_podaci) {
                return $this->prikaz_azuriranje_izlozbi($id_izlozbe);
            }
            $uneseni_podaci["id_izlozbe"] = $id_izlozbe;
            $uneseni_podaci["id_moderatora"] = $this->_korisnici_repo->dohvati_prema_korisnicko_ime($this->_korisnicko_ime)->id;
            $this->_odluka_o_zapisu_izlozbe($uneseni_podaci);

            $this->preusmjeri(Postavke::dohvati_server_url() . "izlozbe/prikaz_uredivanje_izlozba/");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikaz_azuriranje_izlozbi($id_izlozbe);
        }
    }

    private function _odluka_o_zapisu_izlozbe($uneseni_podaci) {
        if (empty($uneseni_podaci["id_izlozbe"])) {
            $this->_izlozba_repo->kreiraj($uneseni_podaci);
            $uneseni_podaci["id_izlozbe"] = $this->_izlozba_repo->dohvati_id_izlozbe_prema_modelu($uneseni_podaci);
            $this->_glasovanje_repo->kreiraj($uneseni_podaci);
        } else {
            $this->_izlozba_repo->azuriraj($uneseni_podaci);
            $this->_glasovanje_repo->azuriraj($uneseni_podaci);
        }
    }

    function prikaz_azuriranje_izlozbi($id_izlozbe) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::MODERATOR);

        $podaci_fronta["naslov_stranice"] = "Kreiranje nove izložbe vlakova";
        $podaci_fronta["opis_stranice"] = "Moderat posjeduje mogućnost kreiranja nove izložbe ili modificiranje postojećih izložbi";
        $podaci_fronta["id"] = "";

        if ($this->_uloga_prijavljenog_korisnika === uloga_korisnika::ADMINISTRATOR) {
            $podaci_fronta["popis_teme_izlozbe"] = $this->_moderatori_repo->dohvati_popis_tematike_administratora();
        }
        if ($this->_uloga_prijavljenog_korisnika === uloga_korisnika::MODERATOR) {
            $podaci_fronta["popis_teme_izlozbe"] = $this->_moderatori_repo->dohvati_popis_tematike_moderatora($this->_korisnicko_ime);
        }

        if (!empty($id_izlozbe)) {
            $podaci_izlozbe = $this->_izlozba_repo->dohvati_prema_id($id_izlozbe);
            $podaci_fronta["uredi_temu"] = $podaci_izlozbe->tematika_id;
            $podaci_fronta["uredi_datum"] = explode(" ", $podaci_izlozbe->datum_pocetka);
            $podaci_fronta["uredi_max_korisnika"] = $podaci_izlozbe->broj_korisnika;
            $podaci_fronta["uredi_Datum"] = $this->_glasovanje_repo->dohvati_glasovanje_prema_izlozbi($id_izlozbe);
            $podaci_fronta["id"] = $id_izlozbe;
        }

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("azuriranje_izlozbe");
    }

    function obrisi_izlozbu($id_izlozbe) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::MODERATOR);

        $this->_izlozba_repo->obrisi($id_izlozbe);
        $this->preusmjeri(Postavke::dohvati_server_url() . "izlozbe/prikaz_uredivanje_izlozba/");
    }

    function prikazi_izlozbe() {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::PRIJAVLJENI_KORISNIK);

        $podaci_fronta["naslov_stranice"] = "Izložbe vlakova";
        $podaci_fronta["opis_stranice"] = "Prijavljeni korisnici mogu na ovoj stranici prijaviti svoj/e vlakove na prikazane izlozbe.";
        $podaci_fronta["izlozba"] = $this->_izlozba_repo->dohvati_aktualne_izlozbe();
        $izborSlike = array("Moderni" => "Moderni vlak", "Brzi" => "Najbrži vlak", "Motorni" => "Motorni vlak",
            "Najmanji" => "Najmanji vlak", "Ogromni" => "Najveći vlak", "Parni" => "Lokomotive");
        $podaci_fronta["izborSlike"] = $izborSlike;

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("prikaz_izlozba");
    }

    function prijavi_vlak_na_izlozbu($id_izlozbe) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::PRIJAVLJENI_KORISNIK);

        try {
            $popis_gresaka = array("odabirVlakaZaPrijavu" => "odabir svojeg vlaka");
            $uneseni_podaci = $this->provjeri_popunjesnost_obaveznih_podataka($popis_gresaka);

            if (!$uneseni_podaci) {
                return $this->prikazi_detalje_izlozbe($id_izlozbe);
            }

            $uneseni_podaci["id_izlozbe"] = $id_izlozbe;
            $this->_prijava_vlaka_repo->kreiraj($uneseni_podaci);

            $this->preusmjeri(Postavke::dohvati_server_url() . "izlozbe/prikazi_detalje_izlozbe/{$id_izlozbe}/");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikazi_detalje_izlozbe($id_izlozbe);
        }
    }

    function prikazi_detalje_izlozbe($id_izlozbe) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::PRIJAVLJENI_KORISNIK);

        $izborSlike = array("Moderni" => "Moderni vlak", "Brzi" => "Najbrži vlak", "Motorni" => "Motorni vlak",
            "Najmanji" => "Najmanji vlak", "Ogromni" => "Najveći vlak", "Parni" => "Lokomotive");

        $podaci_fronta["naslov_stranice"] = "Izložbe vlakova";
        $podaci_fronta["opis_stranice"] = "Prijavljeni korisnici mogu na ovoj stranici prijaviti svoj/e vlakove na prikazane izlozbe.";
        $podaci_fronta["izlozba"] = $this->_izlozba_repo->dohvati_aktualne_izlozbe($id_izlozbe)[0];
        $podaci_fronta["izborSlike"] = $izborSlike;
        $podaci_fronta["korisnicko_ime"] = $this->_korisnicko_ime;
        $podaci_fronta["slobodni_vlakovi_korisnika"] = $this->_vlak_repo->dohvati_slobodne_vlakove_korisnika($id_izlozbe);
        $podaci_fronta["popis_prijavljenih_korisnika"] = $this->_prijava_vlaka_repo->dohvati_prijavljene_korisnike_sa_izlozbe($id_izlozbe);
        $podaci_fronta["id"] = $id_izlozbe;
        $podaci_fronta["vrsta_materijala"] = $this->_vrsta_materijala_repo->dohvati_listu();

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("prikaz_detalja_izlozbi");
    }

    function obrisi_vlak_sa_izlozbe($id_prijave_vlaka) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::PRIJAVLJENI_KORISNIK);

        $this->_prijava_vlaka_repo->obrisi($id_prijave_vlaka);
        $this->preusmjeri(Postavke::dohvati_server_url() . "izlozbe/prikazi_izlozbe/");
    }

    function ocjeni_prijavljenog_vlaka($prijava_vlaka_id) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::PRIJAVLJENI_KORISNIK);

        $id_izlozbe = $this->_prijava_vlaka_repo->dohvati_id_izlozbe($prijava_vlaka_id);
        try {
            $popis_gresaka = array("ocjena" => "ocjenu korisnika", "komentar");
            $uneseni_podaci = $this->provjeri_popunjesnost_obaveznih_podataka($popis_gresaka);

            if (!$uneseni_podaci) {
                return $this->prikazi_detalje_izlozbe($id_izlozbe);
            }

            $uneseni_podaci["prijava_vlaka_id"] = $prijava_vlaka_id;
            $this->_odluka_o_zapisu_ocjene($uneseni_podaci);

            $this->preusmjeri(Postavke::dohvati_server_url() . "izlozbe/prikazi_detalje_izlozbe/{$id_izlozbe}/");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikazi_detalje_izlozbe($id_izlozbe);
        }
    }

    private function _odluka_o_zapisu_ocjene($uneseni_podaci) {
        $id_ocjene = $this->_ocjena_repo->dohvati_id_ocjene_prema_modelu($uneseni_podaci["prijava_vlaka_id"]);

        if (!$id_ocjene) {
            $this->_ocjena_repo->kreiraj($uneseni_podaci);
        } else {
            $uneseni_podaci["id_ocjene"] = $id_ocjene;
            $this->_ocjena_repo->azuriraj($uneseni_podaci);
        }
    }

}
