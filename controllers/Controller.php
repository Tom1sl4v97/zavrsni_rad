<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author franj
 */
include_once ROOT . "vanjske_biblioteke/smarty-3.1.39/libs/Smarty.class.php";
include_once ROOT . "utils/Postavke.php";
include_once ROOT . "utils/Sesija.php";
include_once ROOT . "models/Dnevnik.php";
include_once ROOT . "repositories/Dnevnik_db_repozitorij.php";

class Controller {

    var $podaci_stranice = [];
    var $smarty;
    private $_virtualno_vrijeme;
    private $_putanja;
    private $_korisnicko_ime;

    public function __construct() {
        $this->_virtualno_vrijeme = Postavke::dohvati_virtualno_vrijeme();
        $this->_putanja = Postavke::dohvati_server_url();
        $this->_korisnicko_ime = Sesija::dohvati_korisnicko_ime();
        $dnevnik_repo = new Dnevnik_db_repozitorij();

        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(ROOT . "templates")
                ->setCompileDir(ROOT . "templates_c")
                ->setPluginsDir(SMARTY_PLUGINS_DIR)
                ->setCacheDir(ROOT . "cache")
                ->setConfigDir(ROOT . "configs");

        Sesija::kreiraj_sesiju();
        
        if (isset($this->_korisnicko_ime)) {
            $this->_provjera_isticanja_sesije();
            $dnevnik_repo->kreiraj_dnevnik($this->_korisnicko_ime, Tip_dnevnika::OSTALE_RADNJE);
        }
    }

    function pripremi_podatke_stranice($podaci) {
        $this->podaci_stranice = array_merge($this->podaci_stranice, $podaci);
    }

    function pripremi_greske($greske) {
        if (isset($greske)) {
            if (!isset($this->podaci_stranice["greske"])) {
                $this->podaci_stranice["greske"] = [];
            }
            if (!is_array($greske)) {
                $greske = array($greske);
            }
            $this->podaci_stranice["greske"] = array_merge($this->podaci_stranice["greske"], $greske);
        }
    }

    function iscrtaj($naziv_predloska) {
        $url_predloska = ucfirst(str_replace('_controller', '', get_class($this))) . '/' . $naziv_predloska . '.tpl';

        $navigacijski_podaci["putanja"] = $this->_putanja;
        $navigacijski_podaci["dizajn"] = Sesija::DIZAJN;
        $navigacijski_podaci["virtualnoVrijeme"] = $this->_virtualno_vrijeme;
        $navigacijski_podaci["url_stranice"] = "$_SERVER[REQUEST_URI]";

        $this->pripremi_podatke_stranice($navigacijski_podaci);
        $this->smarty->assign($this->podaci_stranice);

        $this->smarty->display("dijeljeno/navigacija.tpl");

        if (isset($this->podaci_stranice["greske"])) {
            $this->smarty->display("dijeljeno/greske.tpl");
        }

        $this->smarty->display($url_predloska);
        $this->smarty->display("dijeljeno/podnozje.tpl");
    }

    function preusmjeri($url_stranice) {
        header("LOCATION: " . $url_stranice);
    }

    public function provjeri_popunjesnost_obaveznih_podataka($popis_uvjeta) {
        $lista_greskaka = [];
        $model = array();

        foreach ($_POST as $kljuc => $vrijednost) {
            $model[$kljuc] = $vrijednost;
            if (empty($popis_uvjeta[$kljuc])) {
                CONTINUE;
            }

            if (empty($vrijednost)) {
                $greska = "Niste popunili: " . $popis_uvjeta[$kljuc] . "<br>";
                array_push($lista_greskaka, $greska);
            }
        }

        if (empty($lista_greskaka)) {
            return $model;
        }

        $this->pripremi_greske($lista_greskaka);
        return FALSE;
    }

    public function provjera_autorizacije_korisnika($uloga_autorizacije) {
        $uloga_korisnika = Sesija::dohvati_ulogu_korisnika();
        if (!isset($uloga_korisnika)) {
            $this->preusmjeri($this->_putanja . "korisnici/podaci_na_prijavu/");
        } elseif ($uloga_korisnika > $uloga_autorizacije) {
            $this->preusmjeri($this->_putanja . "pocetna_stranica/index/");
        }
    }

    private function _provjera_isticanja_sesije() {
        if ($this->_vezli_li_sesija()) {
            Sesija::obrisi_sesiju();
            $this->preusmjeri($this->_putanja . "korisnici/podaci_na_prijavu/istekla_sesija/");
        } else {
            Sesija::kreiraj_vrijeme_korisnika($this->_virtualno_vrijeme);
        }
    }

    private function _vezli_li_sesija() {
        $vrijeme_trajanja_sesije = Postavke::dohvati_txt_zapis_dokumenta("trajanje_sesije");

        $datum1 = explode(" ", Sesija::dohvati_vrijeme_korisnika());
        $datum2 = explode(" ", $this->_virtualno_vrijeme);

        $pocetnoVrijeme = new DateTime($datum1[1]);
        $treutnoVrijeme = new DateTime($datum2[1]);
        $razlika = $pocetnoVrijeme->diff($treutnoVrijeme);
        $vrijemeOduzimanja = $razlika->format('%H:%I:%S');

        return $vrijeme_trajanja_sesije < $vrijemeOduzimanja;
    }

}
