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
interface Bazni_repozitorij_interface{

    public function dohvati_prema_id($id);

    public function kreiraj($uneseni_podaci);

    public function dohvati_listu();

    public function obrisi($id_zapisa);

    public function azuriraj($uneseni_podaci);
}
