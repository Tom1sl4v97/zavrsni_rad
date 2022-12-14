<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Router
 *
 * @author franj
 */
class Router {

    static public function parse($url, $zahtjev) {
        $bazni_url = trim($url);

        if ($bazni_url == "/Projektni_zadatak/") {
            $zahtjev->kontroler = "Pocetna_stranica";
            $zahtjev->akcija = "index";
            $zahtjev->parametar = [];
        } else {
            $puni_url = explode('/', $bazni_url);
            $podijeljeni_url = array_slice($puni_url, 2);
            $zahtjev->kontroler = $podijeljeni_url[0];
            $zahtjev->akcija = $podijeljeni_url[1];
            $zahtjev->parametar = array_slice($podijeljeni_url, 2);
        }
    }

}
