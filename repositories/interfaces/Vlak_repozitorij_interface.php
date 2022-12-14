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

interface Vlak_repozitorij_interface extends Bazni_repozitorij_interface {

    public function prikaz_vlakova_korisnika();

    public function dohvati_slobodne_vlakove_korisnika($model);

    public function zapisi_podatke_iz_sigurosne_kopije($zapis);

    public function obrisi_sve_vlakove();
}
