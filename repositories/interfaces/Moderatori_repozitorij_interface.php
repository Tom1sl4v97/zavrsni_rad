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

interface Moderatori_repozitorij_interface extends Bazni_repozitorij_interface {

    public function dohvati_popis_moderatora();

    public function dohvati_popis_tematike_moderatora($korisnicko_ime);
}
