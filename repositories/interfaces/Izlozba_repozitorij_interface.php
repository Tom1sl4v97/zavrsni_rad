<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author franj
 */
require_once(ROOT . 'repositories/interfaces/Bazni_repozitorij_interface.php');

interface Izlozba_repozitorij_interface extends Bazni_repozitorij_interface {

    public function dohvati_listu_izlozbi_moderatora($korisnicko_ime);

    public function dohvati_id_izlozbe_prema_modelu($uneseni_podaci);

    public function dohvati_aktualne_izlozbe($id_izlozbe = "");

    public function dohvati_zavrsene_izlozbe();

    public function dohvati_pobjednika_izlozbe($id_izlozbe);
}
