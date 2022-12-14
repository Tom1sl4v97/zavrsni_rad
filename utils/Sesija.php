<?php

/*
 * The MIT License
 *
 * Copyright 2014 Matija Novak <matija.novak@foi.hr>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * Klasa za upravljanje sa sesijama
 *
 * @author Matija Novak <matija.novak@foi.hr>
 */
require_once ROOT . "utils/Postavke.php";

class Sesija {

    const KORISNIK = "korisnik";
    const ULOGA = "uloga";
    const SESSION_NAME = "prijava_sesija";
    const VRIJEME = "vrijeme";
    const DIZAJN = "dizajn";
    const DARKMODE = "darkmode";

    static function kreiraj_sesiju() {
        if (session_id() == "") {
            session_name(self::SESSION_NAME);
            session_start();
        }
    }

    static function kreiraj_korisnika($korisnik, $vrijeme, $uloga = 4, $dizajn = "disabled", $darkmode = "disabled") {
        self::kreiraj_sesiju();
        $_SESSION[self::KORISNIK] = $korisnik;
        $_SESSION[self::ULOGA] = $uloga;
        $_SESSION[self::VRIJEME] = $vrijeme;
        $_SESSION[self::DIZAJN] = $dizajn;
        $_SESSION[self::DARKMODE] = $darkmode;
    }

    static function dohvati_korisnicko_ime() {
        self::kreiraj_sesiju();
        if (isset($_SESSION[self::KORISNIK])) {
            $korisnicko_ime = $_SESSION[self::KORISNIK];
        } else {
            return NULL;
        }
        return $korisnicko_ime;
    }

    static function dohvati_ulogu_korisnika() {
        self::kreiraj_sesiju();
        if (isset($_SESSION[self::ULOGA])) {
            $uloga = $_SESSION[self::ULOGA];
        } else {
            return NULL;
        }
        return $uloga;
    }

    static function kreiraj_vrijeme_korisnika($vrijeme) {
        self::kreiraj_sesiju();
        $_SESSION[self::VRIJEME] = $vrijeme;
    }

    static function dohvati_vrijeme_korisnika() {
        self::kreiraj_sesiju();
        if (isset($_SESSION[self::VRIJEME])) {
            $vrijeme = $_SESSION[self::VRIJEME];
        } else {
            return NULL;
        }
        return $vrijeme;
    }

    static function kreiraj_darkmode_korisnika($darkmode = "") {
        self::kreiraj_sesiju();
        $_SESSION[self::DARKMODE] = $darkmode;
    }

    static function dohvati_darkmode_korisnika() {
        self::kreiraj_sesiju();
        if (isset($_SESSION[self::DARKMODE])) {
            $darkmode = $_SESSION[self::DARKMODE];
        } else {
            return NULL;
        }
        return $darkmode;
    }

    static function kreiraj_dizajn_korisnika($dizajn = "") {
        self::kreiraj_sesiju();
        $_SESSION[self::DIZAJN] = $dizajn;
    }

    static function dohvati_dizajn_korisnika() {
        self::kreiraj_sesiju();
        if (isset($_SESSION[self::DIZAJN])) {
            $dizajn = $_SESSION[self::DIZAJN];
        } else {
            return NULL;
        }
        return $dizajn;
    }

    static function obrisi_sesiju() {
        session_name(self::SESSION_NAME);

        if (session_id() != "") {
            session_unset();
            session_destroy();
        }
    }

}
