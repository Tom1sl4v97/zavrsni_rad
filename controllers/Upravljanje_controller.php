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
include_once(ROOT . 'repositories/Dnevnik_db_repozitorij.php');
include_once(ROOT . 'repositories/Vlak_db_repozitorij.php');
include_once(ROOT . 'repositories/Ocjena_db_repozitorij.php');
include_once(ROOT . 'repositories/Prijava_vlaka_db_repozitorij.php');
include_once(ROOT . 'repositories/Materijal_db_repozitorij.php');
include_once(ROOT . 'utils/Postavke.php');
include_once(ROOT . 'models/Korisnik.php');

class Upravljanje_controller extends Controller {

    private const BARKA_POMAK_VREMENA_URL = "http://barka.foi.hr/WebDiP/pomak_vremena/pomak.php?format=json";

    private $_korisnici_repo;
    private $_dnevnik_repo;
    private $_vlak_repo;
    private $_ocjena_repo;
    private $_prijava_vlaka_repo;
    private $_materijal_repo;
    private $_url_servera;

    public function __construct() {
        parent::__construct();
        $this->_korisnici_repo = new Korisnici_db_repozitorij();
        $this->_dnevnik_repo = new Dnevnik_db_repozitorij();
        $this->_vlak_repo = new Vlak_db_repozitorij();
        $this->_ocjena_repo = new Ocjena_db_repozitorij();
        $this->_prijava_vlaka_repo = new Prijava_vlaka_db_repozitorij();
        $this->_materijal_repo = new Materijal_db_repozitorij();
        $this->_url_servera = Postavke::dohvati_server_url();
    }

    function prikazi_postavke_stranice($poruka = "") {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        $podaci_fronta["naslov_stranice"] = "Postavke stranica";
        $podaci_fronta["opis_stranice"] = "Ovo su postavke stranice, kojoj može samo administrator pristupiti";
        $podaci_fronta["trenutni_zapis_sesije"] = Postavke::dohvati_txt_zapis_dokumenta("trajanje_sesije");
        $podaci_fronta["trenutni_zapis_virtualnog_vremena"] = Postavke::dohvati_razliku_virtualnog_vremena();
        $podaci_fronta["trenutni_zapis_kolacica"] = Postavke::dohvati_txt_zapis_dokumenta("trajanje_kolacica");
        $podaci_fronta["popis_korisnika"] = $this->_korisnici_repo->dohvati_listu();
        $podaci_fronta["dnevnik_koristenja_stranice"] = $this->_prikazi_dnevnik_koristenja_stranice();
        $podaci_fronta["urlStranica"] = $this->_preuredi_url_stranice_kod_prikaza($podaci_fronta["dnevnik_koristenja_stranice"]);
        
        if (!empty($poruka)){
            $this->pripremi_greske("Uspješno ste ažurirali sigurnosnu kopiju.");
        }

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("postavke");
    }

    private function _prikazi_dnevnik_koristenja_stranice() {
        $popis_uvjeta = array("pocetniDatum", "zavrsniDatum");
        $uneseni_podaci = $this->provjeri_popunjesnost_obaveznih_podataka($popis_uvjeta);

        return $this->_dnevnik_repo->dohvati_listu($uneseni_podaci);
    }

    private function _preuredi_url_stranice_kod_prikaza($popis_dnevnika) {
        $url = array();
        for ($i = 0; $i < count($popis_dnevnika); $i++) {
            $stranica = explode("/", $popis_dnevnika[$i]->stranica);
            $url[$i] = implode(" /", $stranica);
        }
        return $url;
    }

    function azuriraj_trajanje_sesije() {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        try {
            $popis_gresaka = array("izmjeniSesiju" => "novo vrijeme trajanja sesija");
            $uneseni_podaci = $this->provjeri_popunjesnost_obaveznih_podataka($popis_gresaka);

            if (!$uneseni_podaci) {
                return $this->prikazi_postavke_stranice();
            }
            $this->_postavi_novo_trajanje_sesije($uneseni_podaci);

            $this->preusmjeri($this->_url_servera . "upravljanje/prikazi_postavke_stranice/");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikazi_postavke_stranice();
        }
    }

    private function _postavi_novo_trajanje_sesije($model) {
        $fp = fopen(ROOT . "izvorne_datoteke/trajanje_sesije.txt", "w");
        fwrite($fp, $model["izmjeniSesiju"]);
        fclose($fp);
    }

    function azuriraj_virtualno_vrijeme() {
        try {
            $this->_postavi_novo_virtualno_vrijeme();

            $this->preusmjeri($this->_url_servera . "upravljanje/prikazi_postavke_stranice/");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikazi_postavke_stranice();
        }
    }

    private function _postavi_novo_virtualno_vrijeme() {
        $file_barke = fopen(self::BARKA_POMAK_VREMENA_URL, "r");
        $string = fread($file_barke, 10000);
        $json = json_decode($string, false);
        $pomak_virtualnog_vremena = $json->WebDiP->vrijeme->pomak->brojSati;
        fclose($file_barke);

        $file_virtualnog_vremena = fopen(ROOT . "izvorne_datoteke/virtualno_vrijeme.txt", "w");
        fwrite($file_virtualnog_vremena, $pomak_virtualnog_vremena);
        fclose($file_virtualnog_vremena);
    }

    function azuriraj_trajanje_kolacica() {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        try {
            $popis_gresaka = array("novoTrajanjeKolacica" => "novo trajanje kolacica");
            $uneseni_podaci = $this->provjeri_popunjesnost_obaveznih_podataka($popis_gresaka);

            if (!$uneseni_podaci) {
                return $this->prikazi_postavke_stranice();
            }
            $this->_postavi_novo_trajanje_kolacica($uneseni_podaci);

            $this->preusmjeri($this->_url_servera . "upravljanje/prikazi_postavke_stranice/");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikazi_postavke_stranice();
        }
    }

    private function _postavi_novo_trajanje_kolacica($model) {
        $file = fopen(ROOT . "izvorne_datoteke/trajanje_kolacica.txt", "w");
        fwrite($file, $model["novoTrajanjeKolacica"]);
        fclose($file);
    }

    function resetiraj_uvjete_koristenja() {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        try {
            $this->_korisnici_repo->resetiraj_uvjete_koristenja();
            throw new Exception("Uspješno ste resetirali uvjete korištenja");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikazi_postavke_stranice();
        }
    }

    function blokiraj_korisnika($id_korisnika) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        try {
            $korisnik = $this->_korisnici_repo->dohvati_prema_id($id_korisnika);
            $korisnik->broj_neuspijesnih_prijava = de_blokiraj_korisnika::BLOKIRAJ_KORISNIKA;
            $this->_korisnici_repo->azuriraj($korisnik);

            $porukaKorisniku = "Postovani\nBlokirani Vam je racun.";
            $poruka = wordwrap($porukaKorisniku, 120);
            mail("{$korisnik->email}", "Blokacija racuna", $poruka);

            $this->preusmjeri($this->_url_servera . "upravljanje/prikazi_postavke_stranice/");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikazi_postavke_stranice();
        }
    }

    function de_blokiraj_korisnika($id_korisnika) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        try {
            $korisnik = $this->_korisnici_repo->dohvati_prema_id($id_korisnika);
            $korisnik->broj_neuspijesnih_prijava = de_blokiraj_korisnika::OD_BLOKIRAJ_KORISNIKA;
            $this->_korisnici_repo->azuriraj($korisnik);

            $porukaKorisniku = "Postovani\nDe-blokirani Vam je racun, mozete se ponovno ulogirati.";
            $poruka = wordwrap($porukaKorisniku, 120);
            mail("{$korisnik->email}", "De-blokacija racuna", $poruka);

            $this->preusmjeri($this->_url_servera . "upravljanje/prikazi_postavke_stranice/");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikazi_postavke_stranice();
        }
    }

    function postavi_dark_mode() {
        $trenutno = Sesija::dohvati_darkmode_korisnika();

        if ($trenutno == "disabled") {
            Sesija::kreiraj_darkmode_korisnika();
        }
        if ($trenutno == "") {
            Sesija::kreiraj_darkmode_korisnika("disabled");
        }
        $this->preusmjeri("/Projektni_zadatak/pocetna_stranica/index");
    }

    function postavi_dizajn() {
        $trenutno = Sesija::dohvati_dizajn_korisnika();

        if ($trenutno == "disabled") {
            Sesija::kreiraj_dizajn_korisnika();
        }
        if ($trenutno == "") {
            Sesija::kreiraj_dizajn_korisnika("disabled");
        }
        $this->preusmjeri("/Projektni_zadatak/pocetna_stranica/index");
    }

    function vrati_sigurnosnu_kopiju() {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        try {
            $this->_vlak_repo->obrisi_sve_vlakove();

            $zapis_vlakova = Postavke::dohvati_txt_zapis_dokumenta("sigurosna_kopija_vlakova");
            $this->_vlak_repo->zapisi_podatke_iz_sigurosne_kopije($zapis_vlakova);

            $zapis_prijave_vlakova = Postavke::dohvati_txt_zapis_dokumenta("sigurosna_kopija_prijave_vlakova");
            $this->_prijava_vlaka_repo->zapisi_podatke_iz_sigurosne_kopije($zapis_prijave_vlakova);

            $zapis_ocjene = Postavke::dohvati_txt_zapis_dokumenta("sigurosna_kopija_ocjena");
            $this->_ocjena_repo->zapisi_podatke_iz_sigurosne_kopije($zapis_ocjene);

            $zapis_materijala = Postavke::dohvati_txt_zapis_dokumenta("sigurosna_kopija_materijala");
            $this->_materijal_repo->zapisi_podatke_iz_sigurosne_kopije($zapis_materijala);

            $this->preusmjeri($this->_url_servera . "upravljanje/prikazi_postavke_stranice/poruka");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikazi_postavke_stranice();
        }
    }

    function kreiraj_sigurnosnu_kopiju() {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::ADMINISTRATOR);

        try {
            $vlakovi = $this->_vlak_repo->dohvati_listu();
            $this->_stvori_sigurosnu_kopiju_vlakova($vlakovi);

            $prijava_vlaka = $this->_prijava_vlaka_repo->dohvati_listu();
            $this->_stvori_sigurosnu_kopiju_prijave_vlakova($prijava_vlaka);

            $ocjena = $this->_ocjena_repo->dohvati_listu();
            $this->_stvori_sigurosnu_kopiju_ocjene($ocjena);

            $materijal = $this->_materijal_repo->dohvati_listu();
            $this->_stvori_sigurosnu_kopiju_materijala($materijal);

            $this->preusmjeri($this->_url_servera . "upravljanje/prikazi_postavke_stranice/poruka");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikazi_postavke_stranice();
        }
    }

    private function _stvori_sigurosnu_kopiju_vlakova($vlakovi) {
        $zapis = "";
        foreach ($vlakovi as $kljuc => $model) {
            $zapis .= "(";
            $zapis .= $model->id . ", ";
            $zapis .= "'" . $model->naziv . "', ";
            $zapis .= $model->max_brzina . ", ";
            $zapis .= $model->broj_sjedala . ", ";
            $zapis .= "'" . $model->opis . "', ";
            $zapis .= $model->vrsta_pogona_id . ", ";
            $zapis .= $model->vlasnik_id . ")";

            if ($kljuc < count($vlakovi) - 1) {
                $zapis .= ",\n";
            } else {
                $zapis .= "\n";
            }
        }

        $fp = fopen(ROOT . "izvorne_datoteke/sigurosna_kopija_vlakova.txt", "w");
        fwrite($fp, $zapis);
        fclose($fp);
    }

    private function _stvori_sigurosnu_kopiju_prijave_vlakova($prijava_vlaka) {
        $zapis = "";
        foreach ($prijava_vlaka as $kljuc => $model) {
            $zapis .= "(";
            $zapis .= $model->id . ", ";
            $zapis .= $model->id_vlaka . ", ";
            $zapis .= $model->izlozba_id . ", ";
            if ($model->azurirao_moderator_id) {
                $zapis .= $model->azurirao_moderator_id . ", ";
            } else {
                $zapis .= "null, ";
            }
            if ($model->datum_azuriranja) {
                $zapis .= "'" . $model->datum_azuriranja . "', ";
            } else {
                $zapis .= "null, ";
            }
            $zapis .= $model->id_statusa . ")";

            if ($kljuc < count($prijava_vlaka) - 1) {
                $zapis .= ",\n";
            } else {
                $zapis .= "\n";
            }
        }

        $fp = fopen(ROOT . "izvorne_datoteke/sigurosna_kopija_prijave_vlakova.txt", "w");
        fwrite($fp, $zapis);
        fclose($fp);
    }

    private function _stvori_sigurosnu_kopiju_ocjene($ocjena) {
        $zapis = "";
        foreach ($ocjena as $kljuc => $model) {
            $zapis .= "(";
            $zapis .= $model->id . ", ";
            $zapis .= $model->ocjena_korisnika . ", ";
            if ($model->komentar) {
                $zapis .= "'" . $model->komentar . "', ";
            } else {
                $zapis .= "null, ";
            }
            $zapis .= $model->prijava_vlaka_id . ", ";
            $zapis .= $model->korisnik_id . ")";

            if ($kljuc < count($ocjena) - 1) {
                $zapis .= ",\n";
            } else {
                $zapis .= "\n";
            }
        }

        $fp = fopen(ROOT . "izvorne_datoteke/sigurosna_kopija_ocjena.txt", "w");
        fwrite($fp, $zapis);
        fclose($fp);
    }

    private function _stvori_sigurosnu_kopiju_materijala($materijal) {
        $zapis = "";
        foreach ($materijal as $kljuc => $model) {
            $zapis .= "(";
            $zapis .= $model->id . ", ";
            $zapis .= "'" . $model->url . "', ";
            $zapis .= $model->vrsta_materijala . ", ";
            $zapis .= $model->prijava_vlaka . ")";
            if ($kljuc < count($materijal) - 1) {
                $zapis .= ",\n";
            } else {
                $zapis .= "\n";
            }
        }

        $fp = fopen(ROOT . "izvorne_datoteke/sigurosna_kopija_materijala.txt", "w");
        fwrite($fp, $zapis);
        fclose($fp);
    }

}
