<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html lang="hr">
    <head>
        <title>{$naslov_stranice}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="title" content="{$naslov_stranice}">
        <meta name="author" content="Tomislav Tomiek">
        <meta name="description" content="{$opis_stranice}">
        <meta name="keywords" content="">
        <link rel="icon" href="{$putanja}multimedija/iconica.png">

        <link rel="stylesheet" href="{$putanja}CSS/ttomiek.css"/>
        <link rel="stylesheet" href="{$putanja}CSS/ttomiek_accesibility.css" 
              {if !isset($smarty.session.dizajn)}
                  disabled
              {else}
                  {$smarty.session.dizajn}
              {/if}
              />
        <link rel="stylesheet" href="{$putanja}CSS/darkmode.css" 
              {if !isset($smarty.session.darkmode)}
                  disabled
              {else}
                  {$smarty.session.darkmode}
              {/if}
              />
    </head>
    <div id="kutijaDizajnaAccessibility">
        <form style="border: none;background-color: transparent;" action="{$putanja}upravljanje/postavi_dizajn/">
            <button type="submit" name="promjenaDizajna" class="slikaGumbaAccessibility" value="disable"></button>
        </form>
    </div>
    <div id="kutijaDizajnaDarkMode">
        <form style="border: none;background-color: transparent;" action="{$putanja}upravljanje/postavi_dark_mode/">
            <button class="slikaGumbaDarkMode" type="submit"></button>
        </form>
    </div>
    <body>
        <header>
            <h1> 
                <img src="{$putanja}multimedija/train.png" alt="vlak" width="70" style="float:left;margin:0px;padding: 0px" />
                <a href="#sekcija_sadržaj">{$naslov_stranice}</a>
            </h1>
        </header>
        <nav>
            <ul>
                <li><a href="{$putanja}pocetna_stranica/index/">Početna stranica</a></li>
                <li><a href="{$putanja}pocetna_stranica/autor_stranice/">Autor</a></li>

                {if !isset($smarty.session.uloga)}
                    <li><a href="{$putanja}korisnici/podaci_na_prijavu/">Prijava</a></li>
                    {/if}

                {if isset($smarty.session.uloga) && $smarty.session.uloga < 4}
                    <li><a href="{$putanja}izlozbe/prikazi_izlozbe/">Izložbe</a></li>
                    <li><a href="{$putanja}vlakovi/prikaz_vlakova_korisnika/">Vaši vlakovi</a></li>
                    {/if}

                {if isset($smarty.session.uloga) && $smarty.session.uloga < 3}
                    <li><a href="{$putanja}izlozbe/prikaz_uredivanje_izlozba/">Uređivanje izložba</a></li>
                    <li><a href="{$putanja}Vlakovi/prikaz_prijava/">Pregled prijava</a></li>
                    {/if}

                {if isset($smarty.session.uloga) && $smarty.session.uloga == 1}
                    <li><a href="{$putanja}izlozbe/prikaz_administracija_izlozba/">Administracija izložba</a></li>
                    <li><a href="{$putanja}upravljanje/prikazi_postavke_stranice/">Postavke</a></li>
                    {/if}

                {if isset($smarty.session.uloga) && $smarty.session.uloga < 4}
                    <li><a href="{$putanja}korisnici/odjava/">Odjava</a></li>
                    {/if}
            </ul>
        </nav>
