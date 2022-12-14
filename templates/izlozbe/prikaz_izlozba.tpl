<link rel="stylesheet" href="{$putanja}CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}CSS/popUp_prozor.css"/>
<link rel="stylesheet" href="{$putanja}CSS/slider_slike.css"/>
<link rel="stylesheet" href="{$putanja}CSS/prikaz_izlozbe.css"/>

<section>
    <div id="slider">
        <figure>
            <img src="{$putanja}multimedija/vlak1.jpg" class="prikazSlike">
            <img src="{$putanja}multimedija/vlak2.jpg" class="prikazSlike">
            <img src="{$putanja}multimedija/vlak3.jpg" class="prikazSlike">
            <img src="{$putanja}multimedija/vlak4.jpg" class="prikazSlike">
            <img src="{$putanja}multimedija/vlak5.jpg" class="prikazSlike">
        </figure>
    </div>
    {if isset($izlozba)}
        <div id="prikazIzlozbe">
            <br><br>
            <h2>
                Dostupne izložbe:
            </h2>
            <br>
            <div class="resetka">
                {section name=i loop=$izlozba}
                    <div style="margin-bottom: 30px">
                        <div class="izlozbaVlakova">
                            <div class="slika">
                                {assign "nemaSlike" "ne"}
                                {foreach from=$izborSlike key=key item=val}
                                    {if $izlozba[i]->naziv_tematike == $val}
                                        <img src="{$putanja}multimedija/prikazTeme/{$key}.jpg" alt="{$key}" style="width: 100%;margin: 0; padding: 0;">
                                        {assign "nemaSlike" "da"}
                                    {/if}
                                {/foreach}
                                {if $nemaSlike == "ne"}
                                    <img src="{$putanja}multimedija/prikazTeme/ostalo.jpg" alt="{$izlozba[i]->naziv_tematike}" style="width: 100%;margin: 0; padding: 0;">
                                {/if}
                            </div>
                            <div class="naslov">
                                {$izlozba[i]->naziv_tematike}
                            </div>
                            <div>
                                <a class="dodaj" href="{$putanja}izlozbe/prikazi_detalje_izlozbe/{$izlozba[i]->id}">DETALJI IZLOŽBE</a>
                                <div class="podkategorije">
                                    {$izlozba[i]->datum_pocetka|date_format: "Datum početka: %d.%m.%Y.<br><br>Vrijeme početka: %H:%M"}<br><br>
                                    Popunjeno: {$izlozba[i]->trenutni_broj_korisnika} / {$izlozba[i]->broj_korisnika}<br><br>
                                    Status: {$izlozba[i]->status_izlozbe}
                                </div>
                            </div>
                        </div>
                    </div>
                {/section}
            </div>
        </div>
    {/if}
</section>
