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

interface Materijal_repozitorij_interface extends Bazni_repozitorij_interface {

    public function dohvati_materijale_prema_modelu($id_prijave_vlaka, $format);

    public function zapisi_podatke_iz_sigurosne_kopije($zapis_materijala);
}
