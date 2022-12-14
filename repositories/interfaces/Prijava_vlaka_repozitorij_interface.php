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

interface Prijava_vlaka_repozitorij_interface extends Bazni_repozitorij_interface {

    public function prikaz_informacija_administratora();

    public function prikaz_informacija_moderatora($korisnicko_ime);

    public function provjeri_broj_korisnika_na_izlozbi($id_prijave_vlaka);

    public function dohvati_prijavljene_korisnike_sa_izlozbe($id_izlozbe);

    public function dohvati_detalje_prijavljenog_vlaka($id_prijave_vlaka);

    public function dohvati_id_izlozbe($id_prijava_vlaka);

    public function dohvati_detalje_prijavljenih_korisnika_kod_zavrsenih_izlozbi($id_izlozbe);

    public function zapisi_podatke_iz_sigurosne_kopije($zapis_prijave_vlakova);
}
