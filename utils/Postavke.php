<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Postavke
 *
 * @author franj
 */
require_once (ROOT . "utils/Sesija.php");

class Postavke {

    private const BARKA_POMAK_VREMENA_URL = "http://barka.foi.hr/WebDiP/pomak_vremena/pomak.php?format=json";

    public static function dohvati_virtualno_vrijeme() {
        $pomak_virtualnog_vremena = self::dohvati_txt_zapis_dokumenta("virtualno_vrijeme");
        
        $vrijeme_servera = time();
        $virtualno_vrijeme = date('Y-m-d H:i:s', ($vrijeme_servera + ($pomak_virtualnog_vremena * 60 * 60)));
        
        return $virtualno_vrijeme;
    }
    
    public static function dohvati_razliku_virtualnog_vremena() {
        $fp = fopen(self::BARKA_POMAK_VREMENA_URL, "r");
        $string = fread($fp, 10000);
        $json = json_decode($string, false);
        $sati = $json->WebDiP->vrijeme->pomak->brojSati;
        fclose($fp);

        return $sati;
    }

    public static function dohvati_txt_zapis_dokumenta($naziv_dokumenta) {
        $url = ROOT . "izvorne_datoteke/{$naziv_dokumenta}.txt";
        $fp = fopen($url, "r");
        $zapis = fread($fp, filesize($url));
        fclose($fp);

        return $zapis;
    }

    public static function dohvati_server_url() {
        return "http://" . $_SERVER['SERVER_NAME'] . "/Projektni_zadatak/";
    }

}
