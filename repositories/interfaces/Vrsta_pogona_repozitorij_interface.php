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

interface Vrsta_pogona_repozitorij_interface extends Bazni_repozitorij_interface {

    public function dohvati_id_vrste_pogona($model);
}
