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

interface Korisnici_repozitorij_interface extends Bazni_repozitorij_interface {

    public function dohvati_prema_korisnicko_ime($korisnicko_ime);

    public function dohvati_moderatore();

    public function aktiviraj_racun_korisnika($korisnik);

    public function resetiraj_uvjete_koristenja();

    public function prihvacivanje_uvjeta_koristenja($id_korisnika);
}
