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
include_once(ROOT . 'repositories/Prijava_vlaka_db_repozitorij.php');
include_once(ROOT . 'repositories/Vlak_db_repozitorij.php');
include_once(ROOT . 'repositories/Vrsta_pogona_db_repozitorij.php');
include_once(ROOT . 'repositories/Materijal_db_repozitorij.php');
include_once(ROOT . 'models/Korisnik.php');
include_once(ROOT . 'models/Prijava_vlaka.php');
include_once(ROOT . 'models/Vrsta_materijala.php');

class Vlakovi_controller extends Controller {

    private $_korisnici_repo;
    private $_prijava_vlaka_repo;
    private $_vlak_repo;
    private $_vrsta_pogona_repo;
    private $_materijal_repo;
    private $_korisnicko_ime;
    private $_uloga_prijavljenog_korisnika;

    public function __construct() {
        parent::__construct();
        $this->_korisnici_repo = new Korisnici_db_repozitorij();
        $this->_prijava_vlaka_repo = new Prijava_vlaka_db_repozitorij();
        $this->_vlak_repo = new Vlak_db_repozitorij();
        $this->_vrsta_pogona_repo = new Vrsta_pogona_db_repozitorij();
        $this->_materijal_repo = new Materijal_db_repozitorij();
        $this->_uloga_prijavljenog_korisnika = Sesija::dohvati_ulogu_korisnika();
        $this->_korisnicko_ime = Sesija::dohvati_korisnicko_ime();
    }

    function prikaz_prijava() {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::MODERATOR);

        $podaci_fronta["naslov_stranice"] = "Lista prijave vlakova";
        $podaci_fronta["opis_stranice"] = "Administratori i moderatori prihvaćuju/odbijaju prijavljene korisnike na izložbu vlakova";
        $podaci_fronta["virtualni_datum"] = Postavke::dohvati_virtualno_vrijeme();

        if ($this->_uloga_prijavljenog_korisnika == uloga_korisnika::ADMINISTRATOR) {
            $podaci_fronta["prikaz_tablice"] = $this->_prijava_vlaka_repo->prikaz_informacija_administratora();
        }
        if ($this->_uloga_prijavljenog_korisnika == uloga_korisnika::MODERATOR) {
            $podaci_fronta["prikaz_tablice"] = $this->_prijava_vlaka_repo->prikaz_informacija_moderatora($this->_korisnicko_ime);
        }
        $this->pripremi_podatke_stranice($podaci_fronta);

        $this->iscrtaj("lista_prijava_vlakova");
    }

    function prihvati_korisnika_na_izlozbu($id_prijave_vlaka) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::MODERATOR);

        try {
            if ($this->_prijava_vlaka_repo->provjeri_broj_korisnika_na_izlozbi($id_prijave_vlaka)) {
                $informacije_prihvacivanja["prihvacivanje_korisnika"] = Prihvacivanje_korisnika::PRIHVATI;
                $informacije_prihvacivanja["id"] = $id_prijave_vlaka;
                $this->_prijava_vlaka_repo->azuriraj($informacije_prihvacivanja);
                $this->preusmjeri(Postavke::dohvati_server_url() . "vlakovi/prikaz_prijava/");
            }
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikaz_prijava($id_prijave_vlaka);
        }
    }

    function odbij_korisnika_na_izlozbu($id_prijava_vlaka) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::MODERATOR);

        $informacije_prihvacivanja["prihvacivanje_korisnika"] = Prihvacivanje_korisnika::ODBIJ;
        $informacije_prihvacivanja["id"] = $id_prijava_vlaka;
        $this->_prijava_vlaka_repo->azuriraj($informacije_prihvacivanja);
        $this->preusmjeri(Postavke::dohvati_server_url() . "vlakovi/prikaz_prijava/");
    }

    function prikaz_vlakova_korisnika() {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::PRIJAVLJENI_KORISNIK);

        $podaci_fronta["naslov_stranice"] = "Vaši vlakovi";
        $podaci_fronta["opis_stranice"] = "Stranica prikazuje dodane vlakove korisnika i omogućuje upravljanje s njima.";
        $podaci_fronta["lista_vlakova"] = $this->_vlak_repo->prikaz_vlakova_korisnika();

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("prikaz_vlakova");
    }

    function azuriraj_vlak_korisnika($id_vlaka) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::PRIJAVLJENI_KORISNIK);

        try {
            $popis_gresaka = array("nazivVlaka" => "naziv vlaka", "maxBrzina" => "maksimalnu brzinu vlaka",
                "brojSjedala" => "broj sjedala vlaka", "opisVlaka" => "opis vlaka", "vrstaPognona" => "odabir vrste pogona",
                "noviNazivPogona" => "naziv novog pogona", "noviOpisPogona" => "opis novog pogona");
            $uneseni_podaci = $this->provjeri_popunjesnost_obaveznih_podataka($popis_gresaka);

            if (!$uneseni_podaci) {
                return $this->prikaz_podataka_kod_azuriranja_vlak($id_vlaka);
            }

            $podaci_korisnika = $this->_korisnici_repo->dohvati_prema_korisnicko_ime($this->_korisnicko_ime);
            $uneseni_podaci["vlasnik_id"] = $podaci_korisnika->id;
            $uneseni_podaci["id_azuriranja"] = $id_vlaka;

            $this->_odluka_zapisa_podataka($uneseni_podaci, $id_vlaka);

            $this->preusmjeri(Postavke::dohvati_server_url() . "vlakovi/prikaz_vlakova_korisnika/");
        } catch (Exception $e) {
            $this->pripremi_greske($e->getMessage());
            $this->prikaz_podataka_kod_azuriranja_vlak($id_vlaka);
        }
    }

    function prikaz_podataka_kod_azuriranja_vlak($id_vlaka = "") {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::PRIJAVLJENI_KORISNIK);

        $podaci_fronta["naslov_stranice"] = "Dodavanje novog vlaka";
        $podaci_fronta["opis_stranice"] = "Navedenom stranicom upravljate vašim vlakom.";
        $podaci_fronta["vrsta_pogona"] = $this->_vrsta_pogona_repo->dohvati_listu();
        $podaci_fronta["id"] = "";

        if (!empty($id_vlaka)) {
            $podaci_fronta["podaci_vlaka"] = $this->_vlak_repo->dohvati_prema_id($id_vlaka);
            $podaci_fronta["id"] = $id_vlaka;
        }

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("azuriranje_vlaka");
    }

    private function _odluka_zapisa_podataka($uneseni_podaci, $id_vlaka) {
        if ($this->_provjera_unosa_novog_pogona($uneseni_podaci)) {
            $uneseni_podaci["vrstaPognona"] = $this->_pripremi_odabranu_vrstu_pogona($uneseni_podaci);
        }

        if (empty($id_vlaka)) {
            $this->_vlak_repo->kreiraj($uneseni_podaci);
        }
        if (!empty($id_vlaka)) {
            $uneseni_podaci["id_vlaka"] = $id_vlaka;
            $this->_vlak_repo->azuriraj($uneseni_podaci);
        }
    }

    private function _provjera_unosa_novog_pogona($uneseni_podaci) {
        return $uneseni_podaci["noviNazivPogona"] != "nijePopunjeno" AND $uneseni_podaci["noviOpisPogona"] != "nijePopunjeno";
    }

    private function _pripremi_odabranu_vrstu_pogona($uneseni_podaci) {
        $this->_vrsta_pogona_repo->kreiraj($uneseni_podaci);
        $vrsta_pogona = $this->_vrsta_pogona_repo->dohvati_id_vrste_pogona($uneseni_podaci);
        return $vrsta_pogona->id;
    }

    function obrisi_valk_korisnika($id_vlaka) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::PRIJAVLJENI_KORISNIK);
        $this->_vlak_repo->obrisi($id_vlaka);
        $this->preusmjeri(Postavke::dohvati_server_url() . "vlakovi/prikaz_vlakova_korisnika/");
    }

    function prikaz_detalja_prijavljenog_vlaka($id_prijave_vlaka) {
        $podaci_fronta["naslov_stranice"] = "Prikaz detalja korisnika";
        $podaci_fronta["opis_stranice"] = "Stranice prikazuje sve detalje prijavljenog korisnika na odabranoj izlozbi. Korisnici mogu gledati materijale vlaka.";
        $podaci_fronta["informacije_vlaka_korisnika"] = $this->_prijava_vlaka_repo->dohvati_detalje_prijavljenog_vlaka($id_prijave_vlaka);
        $podaci_fronta["slike_korisnika"] = $this->_materijal_repo->dohvati_materijale_prema_modelu($id_prijave_vlaka, format_materijala::SLIKE);
        $podaci_fronta["naziv_slike"] = $this->_dohvati_naziv_sa_url($podaci_fronta["slike_korisnika"]);
        $podaci_fronta["video_korisnika"] = $this->_materijal_repo->dohvati_materijale_prema_modelu($id_prijave_vlaka, format_materijala::VIDEO);
        $podaci_fronta["naziv_videa"] = $this->_dohvati_naziv_sa_url($podaci_fronta["video_korisnika"]);
        $podaci_fronta["audio_korisnika"] = $this->_materijal_repo->dohvati_materijale_prema_modelu($id_prijave_vlaka, format_materijala::AUDIO);
        $podaci_fronta["naziv_audia"] = $this->_dohvati_naziv_sa_url($podaci_fronta["audio_korisnika"]);
        $podaci_fronta["gif_korisnika"] = $this->_materijal_repo->dohvati_materijale_prema_modelu($id_prijave_vlaka, format_materijala::GIF);
        $podaci_fronta["naziv_gifa"] = $this->_dohvati_naziv_sa_url($podaci_fronta["gif_korisnika"]);

        $this->pripremi_podatke_stranice($podaci_fronta);
        $this->iscrtaj("prikaz_detalja_vlaka");
    }

    private function _dohvati_naziv_sa_url($podaci_materijala) {
        for ($i = 0; $i < count($podaci_materijala); $i++) {
            $url = explode("/", $podaci_materijala[$i]->url);
            $pc = count($url);

            $naziv_bez_formata = explode(".", $url[$pc - 1]);
            $naziv[$i] = $naziv_bez_formata[0];
        }
        if (isset($naziv)) {
            return $naziv;
        }
    }

    function ucitaj_materijale_korisnika_na_izlozbu($id_prijave_vlaka) {
        $this->provjera_autorizacije_korisnika(uloga_korisnika::PRIJAVLJENI_KORISNIK);

        $vrsta_materijala = filter_input(INPUT_POST, 'odabirMaterijala');
        $url = $this->_provjera_url_spremanja_materijala($vrsta_materijala);

        for ($i = 0; $i < count($_FILES['upload']['name']); $i++) {
            $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
            if ($tmpFilePath != "") {
                $newFilePath = ROOT . $url . $_FILES['upload']['name'][$i];

                move_uploaded_file($tmpFilePath, $newFilePath);
                $model["korisnicko_ime"] = $this->_korisnicko_ime;
                $model["id_prijave_vlaka"] = $id_prijave_vlaka;
                $model["url"] = Postavke::dohvati_server_url() . $url . $_FILES['upload']['name'][$i];
                $model["vrsta_materijala"] = $vrsta_materijala;

                $this->_materijal_repo->kreiraj($model);
            }
        }
        $id_izlozbe = $this->_prijava_vlaka_repo->dohvati_id_izlozbe($id_prijave_vlaka);
        $this->preusmjeri(Postavke::dohvati_server_url() . "izlozbe/prikazi_detalje_izlozbe/$id_izlozbe");
    }

    private function _provjera_url_spremanja_materijala($vrsta_materijala) {
        $korisnicko_ime = Sesija::dohvati_korisnicko_ime();
        $vrsta = $this->_odabir_formata_materijala($vrsta_materijala);

        $url = ROOT . "multimedija/{$korisnicko_ime}/{$vrsta}/";
        if (!file_exists($url)) {
            $oldmask = umask(0);
            mkdir($url, 0777, true);
            umask($oldmask);
        }
        $url = "multimedija/{$korisnicko_ime}/{$vrsta}/";

        return $url;
    }

    private function _odabir_formata_materijala($id_vrsta_materijala) {
        switch ($id_vrsta_materijala) {
            case 1:
                $format_materijala = "slika";
                break;
            case 2:
                $format_materijala = "audio";
                break;
            case 3:
                $format_materijala = "video";
                break;
            case 4:
                $format_materijala = "gif";
                break;
        }
        return $format_materijala;
    }

}
