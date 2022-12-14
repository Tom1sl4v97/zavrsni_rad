<link rel="stylesheet" href="{$putanja}CSS/popUp_prozor.css"/>
<link rel="stylesheet" href="{$putanja}CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}CSS/prikaz_izlozbe.css"/>
<link rel="stylesheet" href="{$putanja}CSS/prikaz_teblice.css"/>

<script src="{$putanja}javascript/zatvaranje_pop_up_prozora.js" ></script>

<section>
    {if isset($podaci_korisnika)}
        <div class="popUp" id="popUpPrihvaćanjaUvjetaKoristenja" style="display: block">
            <div class="popUpPozadina">
                <div id="odustani" class="odustani"> X </div>
                <h2 style="padding: 0">Prihvaćate li uvjete korištenja, zapis podataka u kolačić o prijavi</h2>
                <h2 id="textIzlozbe" style="padding: 0"></h2>
                <br>
                <div id="bezVlakova"></div>
                <form class="popUpForma">
                    <a class="gumbPrijava" href="{$putanja}korisnici/prihvacivanje_uvjeta_koristenja/{$podaci_korisnika->id}">Prihvaćam</a>
                    <a class="gumbPrijava" href="{$putanja}korisnici/prihvacivanje_uvjeta_koristenja/">Ne prihvaćam</a>
                </form>
            </div>
        </div>
    {/if}

    {if isset($istekloVrijeme)}
        {$istekloVrijeme}
    {else}
        <h2>
            Prikaz završenih izložbi i pobjednika izložbe
        </h2>
        <div class="resetka">
            {section name=i loop=$izlozba}
                <div style="margin-bottom: 30px">
                    <div class="izlozbaVlakova">
                        <div class="slika">
                            {assign "nemaSlike" "ne"}
                            {foreach from=$izbor_slike key=key item=val}
                                {if $izlozba[i]->naziv_tematike == $val}
                                    <img src="{$putanja}/multimedija/prikazTeme/{$key}.jpg" alt="{$key}" style="width: 100%;margin: 0; padding: 0;">
                                    {assign "nemaSlike" "da"}
                                    {assign "naslovnaSlike" $key}
                                {/if}
                            {/foreach}
                            {if $nemaSlike == "ne"}
                                <img src="{$putanja}/multimedija/prikazTeme/ostalo.jpg" alt="{$izlozba[i]->naziv_tematike}" style="width: 100%;margin: 0; padding: 0;">
                            {/if}
                        </div>
                        <div class="naslov">
                            {$izlozba[i]->naziv_tematike}
                            {if {$izlozba[i]->trenutni_broj_korisnika} != 0}
                                <a class="dodaj2" href="{$putanja}pocetna_stranica/index/{$izlozba[i]->id}/">
                                    Detalji prijave
                                </a>
                            {/if}
                        </div>
                        <div>
                            <br>
                            <div class="podkategorije">
                                {$izlozba[i]->datum_pocetka|date_format: "<b>Datum početka:</b> %d.%m.%Y.<br><br><b>Vrijeme početka:</b> %H:%M"}<br><br>
                                <b>Popunjeno:</b> {$izlozba[i]->trenutni_broj_korisnika} / {$izlozba[i]->broj_korisnika}<br><br>
                                <b>Status: </b> {$izlozba[i]->status_izlozbe}
                                {if $podaci_glasanja[i] != NULL}
                                    <p><b>Pobijednik:</b> {$podaci_glasanja[i][0]->ime} {$podaci_glasanja[i][0]->prezime}</p>
                                    <p><b>Naziv vlaka:</b> {$podaci_glasanja[i][0]->naziv_vlaka}
                                    <p><b>Rezultat: </b> {$podaci_glasanja[i][0]->ukupno_glasova} / {$podaci_glasanja[i][0]->ukupno_bodova} (glas/bod)</p>
                                    <form class="prikazForme">
                                        <a class="dodaj" href="{$putanja}vlakovi/prikaz_detalja_prijavljenog_vlaka/{$podaci_glasanja[i][0]->id_prijave_vlaka}/">
                                            Detalji pobjednika
                                        </a>
                                    </form>
                                    <br><br>

                                    {section name=k loop=$podaci_glasanja[i]}
                                        {if $smarty.section.k.index == 0 and $smarty.section.k.total > 1}
                                            <p><b>Ostali sudionici:</b></p>
                                        {elseif $smarty.section.k.total > 1 AND $smarty.section.k.index < 3}
                                            {$smarty.section.k.index_next}. 
                                            {$podaci_glasanja[i][k]->ime} 
                                            {$podaci_glasanja[i][k]->prezime} - 
                                            {$podaci_glasanja[i][k]->korisnicko_ime}: 
                                            {$podaci_glasanja[i][k]->naziv_vlaka} - 
                                            {$podaci_glasanja[i][k]->ukupno_glasova} / 
                                            {$podaci_glasanja[i][k]->ukupno_bodova}
                                            <br>
                                        {/if}
                                    {/section}
                                {else}
                                    <p>Nitko nije glasao</p>
                                {/if}


                            </div>
                        </div>
                    </div>
                </div>
            {/section}
        </div>
        {if isset($detalji_izlozbe)}
            <div id="RSSKanal">
                <br><br>
                <table class="prikazTablice" style="width: 95%">
                    <caption style="font-size: 24px; padding: 0px 0px 15px 0px;">Prikaz detalja prijave korisnika kod odabrane izlozbe</caption>
                    <thead>
                        <tr>
                            <th>Ime</th>
                            <th>Prezime</th>
                            <th>Korisnicko ime</th>
                            <th>E-mail</th>
                            <th>Naziv vlaka</th>
                            <th>Naziv tematike izlozbe</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=i loop=$detalji_izlozbe}
                            <tr>
                                <td>{$detalji_izlozbe[i]->ime_korisnika}</td>
                                <td>{$detalji_izlozbe[i]->prezime_korisnika}</td>
                                <td>{$detalji_izlozbe[i]->korisnicko_ime}</td>
                                <td>{$detalji_izlozbe[i]->email}</td>
                                <td>{$detalji_izlozbe[i]->naziv}</td>
                                <td>{$detalji_izlozbe[i]->naziv_tematike}</td>
                            </tr>
                        {/section}
                    </tbody>
                </table>
                <br><br>
            </div>
        {/if}
    {/if}
</section>

