<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Izlozbe_repozitorij_interface
 *
 * @author franj
 */
interface Dnevnik_repozitorij_interface {

    public function kreiraj_dnevnik($korisnicko_ime, $tip_dnevnika, $upit = "NULL");

    public function dohvati_listu($lista_datuma);
}
