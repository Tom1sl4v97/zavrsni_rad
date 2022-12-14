<link rel="stylesheet" href="{$putanja}CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}CSS/popUp_prozor.css"/>
<link rel="stylesheet" href="{$putanja}CSS/slider_slike.css"/>
<link rel="stylesheet" href="{$putanja}CSS/prikaz_izlozbe.css"/>

<script src="{$putanja}javascript/prikaz_kontrola_kod_izlozbi.js"></script>

<section>
    <div class="popUp" id="dodaj_vlak">
        <div class="popUpPozadina">
            <div id="odustani" class="odustani"> X </div>
            <h2 style="padding: 0">Odaberite željeni vlak za odabranu izložbu:</h2>
            <h2 id="textIzlozbe" style="padding: 0"></h2>
            <br>
            <div id="bezVlakova"></div>
            {if !empty($slobodni_vlakovi_korisnika[0])}
                <form method="post" class="popUpForma" action="{$putanja}izlozbe/prijavi_vlak_na_izlozbu/{$id}">
                    <select name="odabirVlakaZaPrijavu" class="prikazDropDown">
                        <option value="0" class="prikazDropDown">Odaberite</option>
                        {section name=i loop=$slobodni_vlakovi_korisnika}
                            <option value="{$slobodni_vlakovi_korisnika[i]->id}" class="prikazDropDown">
                                {$slobodni_vlakovi_korisnika[i]->naziv}
                            </option>
                        {/section}
                    </select>
                    <br><br>
                    <button type="submit" class="gumbPrijava">Prijavi vlak</button>
                </form>
            {else}
                <h4>Dodali ste već sve vaše vlakove na izložbu ili nemate kreiranje vlastite vlakove.</h4>
            {/if}
        </div>
    </div> 

    <div id="detaljiIzlozbe">
        <h2 class="naslovPravi" id="naslov">{$izlozba->naziv_tematike}</h2>
        {foreach from=$izborSlike key=key item=val}
            {if $izlozba->naziv_tematike == $val}
                <img src="{$putanja}multimedija/prikazTeme/{$key}.jpg" class="slika_izlozbe">
                {assign "nemaSlike" "da"}
            {/if}
        {/foreach}
        {if $nemaSlike == "ne"}
            <img src="{$putanja}multimedija/prikazTeme/ostalo.jpg" alt="{$izlozba->naziv_tematike}" class="slika_izlozbe">
        {/if}

        <img src="" style="width: 55%;margin: 0; padding-top:  30px;float: right" id="slikaNaslova">
        <br><br>
        <h2>Osnovne informacije o izložbi:</h2>
        <br>
        <h3>Datum početka izložbe:</h3>
        <p class="prikaz_informacija">{$izlozba->datum_pocetka|date_format: "%d.%m.%Y."}</p>
        <h3>Vrijeme početka:</h3>
        <p class="prikaz_informacija">{$izlozba->datum_pocetka|date_format: "%H:%M"}</p>
        <h3>Popunjenost izložbe:</h3>
        <p class="prikaz_informacija">{$izlozba->trenutni_broj_korisnika} / {$izlozba->broj_korisnika}</p>
        <h3>Kratki opis o izložbi:</h3>
        <p class="prikaz_informacija">{$izlozba->opis_tematike}</p>
        <br>

        <a class="gumbPrijava2" style="margin-left: 2%" href="{$putanja}izlozbe/prikazi_izlozbe/">Vrati se</a>
        {if $izlozba->status_izlozbe === "Otvorene prijave"}
            <a class="gumbPrijava2" onclick="odabir_vlaka('{$izlozba->naziv_tematike}')">Prijavi svoj vlak</a>
        {/if}

        <br><br><br><br>


        {section name=i loop=$popis_prijavljenih_korisnika}
            <div class="prikazKorisnika">
                <div class="kutija">
                    <img src="{$popis_prijavljenih_korisnika[i]->url_slike}" alt="{$popis_prijavljenih_korisnika[i]->naziv}" class="
                         {if ($smarty.section.i.index % 2) == 0}
                             slikaKorisnikaLijeva
                         {else}
                             slikaKorisnikaDesna
                         {/if}">
                    <div class="
                         {if ($smarty.section.i.index % 2) == 0}
                             prikazTekstaLijevi
                         {else}
                             prikazTekstaDesni
                         {/if}">
                        <a><b>Informacije o vlasniku:</b> {$popis_prijavljenih_korisnika[i]->ime_korisnika} {$popis_prijavljenih_korisnika[i]->prezime_korisnika} - {$popis_prijavljenih_korisnika[i]->korisnicko_ime} </a>
                        <br><br>
                        <a><b>Naziv vlaka:</b> {$popis_prijavljenih_korisnika[i]->naziv}</a>
                        <br><br>
                        <a><b>Maksimalna brzina:</b>  {$popis_prijavljenih_korisnika[i]->max_brzina} &nbsp;&nbsp;&nbsp;&nbsp;<b>Broj sjedala vlaka:</b> {$popis_prijavljenih_korisnika[i]->broj_sjedala}</a>
                        <br><br>
                        <a><b>Vrsta pogona:</b> {$popis_prijavljenih_korisnika[i]->naziv_pogona}
                            <br><br><br>
                            {if $popis_prijavljenih_korisnika[i]->status === "Otvorene prijave" AND $popis_prijavljenih_korisnika[i]->korisnicko_ime === $korisnicko_ime}
                                <a class="gumbPrijava2" onclick="dodaj_materijale({$smarty.section.i.index})">Dodaj materijale</a>
                                <a class="gumbPrijava2" href="{$putanja}izlozbe/obrisi_vlak_sa_izlozbe/{$popis_prijavljenih_korisnika[i]->id}">Obrisi vlak</a>
                            {/if}
                            {if $popis_prijavljenih_korisnika[i]->status === "Otvoreno glasovanje" AND $popis_prijavljenih_korisnika[i]->korisnicko_ime !== $korisnicko_ime}
                                <a class="gumbPrijava2" onclick="otvori_glasovanje({$smarty.section.i.index})">Glasaj</a>
                            {/if}
                            <a class="gumbPrijava2" style="float: right;" href="{$putanja}vlakovi/prikaz_detalja_prijavljenog_vlaka/{$popis_prijavljenih_korisnika[i]->id}">Detalji</a>
                        </a>
                    </div>
                    <br><br>
                </div>
            </div>

            <div class="popUp" id="glasaj{$smarty.section.i.index}">
                <div class="popUpPozadina">
                    <div onclick="zatvori_pop_up_glasovanja({$smarty.section.i.index})" class="odustani"> X </div>
                    <h2 style="padding: 0; margin: 0;">Ocijeni korisnika:</h2>
                    <br>
                    <form method="post" class="popUpForma" action="{$putanja}izlozbe/ocjeni_prijavljenog_vlaka/{$popis_prijavljenih_korisnika[i]->id}">
                        <label>Ocjena (1 - 10):</label>
                        <input class="okvirForme" type="number" name="ocjena" min="1" max="10">
                        <br><br>
                        <label>Komentar:</label>
                        <input class="okvirForme" type="text" name="komentar">
                        <br><br>
                        <button type="submit" class="gumbPrijava">Glasaj</button>
                    </form>
                </div>
            </div>

            <div class="popUp" id="dodaj_materijale{$smarty.section.i.index}">
                <div class="popUpPozadina">
                    <div onclick="zatvori_pop_up_materijala({$smarty.section.i.index})" class="odustani"> X </div>
                    <h2 style="padding: 0">Dodajte željene materijale:</h2>
                    <h2 id="textIzlozbe" style="padding: 0"></h2>
                    <br>
                    <form class="popUpForma" enctype="multipart/form-data" action="{$putanja}vlakovi/ucitaj_materijale_korisnika_na_izlozbu/{$popis_prijavljenih_korisnika[i]->id}/" method="post">
                        <label for="odabirMaterijala">Odaberite vrstu materijala:</label><br>
                        <select name="odabirMaterijala" id="odabirMaterijala" class="prikazDropDown">
                            <option value="0" class="prikazDropDown">Odaberite</option>
                            {section name=brojac loop=$vrsta_materijala}
                                <option value="{$vrsta_materijala[brojac]->id}" class="prikazDropDown">
                                    {$vrsta_materijala[brojac]->format}
                                </option>
                            {/section}
                        </select>
                        <br><br>
                        <input type="hidden" name="MAX_FILE_SIZE" value="3000000000" />
                        <input name="upload[]" type="file" multiple="multiple" class="prikazDropDown"/><br><br>

                        <button type="submit" class="gumbPrijava">Pošalji</button>
                    </form>

                </div>
            </div>           

        {/section}
    </div>
</section>
