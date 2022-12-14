<link rel="stylesheet" href="{$putanja}CSS/prikaz_izlozbe.css"/>
<link rel="stylesheet" href="{$putanja}CSS/prikaz_forme.css"/>

<section>
    {if isset($popis_tematike_vlakova)}
        <h2>
            Tematike vlakova
        </h2>

        <div class="resetka">
            {section name=i loop=$popis_tematike_vlakova}
                {if $popis_tematike_vlakova[i]->vazi_do >= $virtualni_datum OR !isset($popis_tematike_vlakova[i]->vazi_do)}
                    <div style="margin-bottom: 20px; max-width: 400px">
                        <div class="izlozbaVlakova">
                            <div class="slika">
                                {assign "nemaSlike" "ne"}
                                {foreach from=$izbor_slika key=key item=val}
                                    {if $popis_tematike_vlakova[i]->naziv_tematike == $val}
                                        <img src="{$putanja}multimedija/prikazTeme/{$key}.jpg" alt="{$key}" width="100%">
                                        {assign "nemaSlike" "da"}
                                    {/if}
                                {/foreach}
                                {if $nemaSlike == "ne"}
                                    <img src="{$putanja}multimedija/prikazTeme/ostalo.jpg" alt="{$popis_tematike_vlakova[i]->naziv_tematike}" style="width: 100%;margin: 0; padding: 0;">
                                {/if}
                            </div>
                            <div class="naslov">
                                {$popis_tematike_vlakova[i]->naziv_tematike}
                            </div>
                            <div>
                                <div class="podkategorije">
                                    {$popis_tematike_vlakova[i]->opis_tematike}
                                </div>
                                <div class="podkategorije2">
                                    {if $popis_tematike_vlakova[i]->vazi_do != "0000-00-00 00:00:00" and $smarty.session.uloga != 1}
                                        Ističe Vam do: {$popis_tematike_vlakova[i]->vazi_do|date_format:"d.m.Y."}
                                    {else}
                                        Nemate vremenski rok
                                    {/if}
                                </div>
                            </div>
                        </div>
                    </div>
                {/if}
            {/section}
        </div>
        <br>
        <h3>
            Ukupni broj tema vlakova iznosi: {$smarty.section.i.total}
        </h3>
        <br>
    {/if}

    <a class="gumbPrijava2" style="float: right" href="{$putanja}izlozbe/prikaz_azuriranje_izlozbi/">Dodaj novu izlozbu</a>
    <h2>
        Izlozbe
    </h2>


    {if isset($popis_izlozbi)}

        <div class="resetka" style="margin-bottom: 20px;width: 100%">
            {section name=i loop=$popis_izlozbi}
                <div style="margin-bottom: 20px; max-width: 400px">
                    <div class="izlozbaVlakova">
                        <div class="slika">
                            {assign "nemaSlike" "ne"}
                            {foreach from=$izbor_slika key=key item=val}
                                {if $popis_izlozbi[i]->naziv_tematike == $val}
                                    <img src="{$putanja}multimedija/prikazTeme/{$key}.jpg" alt="{$key}" style="width: 100%;margin: 0; padding: 0;">
                                    {assign "nemaSlike" "da"}
                                {/if}
                            {/foreach}
                            {if $nemaSlike == "ne"}
                                <img src="{$putanja}multimedija/prikazTeme/ostalo.jpg" alt="{$popis_izlozbi[i]->naziv_tematike}" style="width: 100%;margin: 0; padding: 0;">
                            {/if}
                        </div>
                        <div class="naslov">
                            {$popis_izlozbi[i]->naziv_tematike}
                        </div>
                        <br>
                        <div>
                            <div class="podkategorije">
                                Datum početka izložbe: {$popis_izlozbi[i]->datum_pocetka|date_format: "%d.%m.%Y <br>otvaranje: %H:%M"}
                            </div>
                            <div class="podkategorije">
                                Maximalni broj korisnika: {$popis_izlozbi[i]->broj_korisnika}
                            </div>
                            <div class="podkategorije">
                                Status izložbe: {$popis_izlozbi[i]->status_izlozbe}
                            </div>
                        </div>
                        {if $popis_izlozbi[i]->status_izlozbe == "Otvorene prijave"}
                            <div style="float: bottom">
                                <div style="padding: 0 5px">
                                    <a class="dodaj" href="{$putanja}izlozbe/prikaz_azuriranje_izlozbi/{$popis_izlozbi[i]->id}">Uredi izlozbu</a>
                                    <a class="dodaj" href="{$putanja}izlozbe/obrisi_izlozbu/{$popis_izlozbi[i]->id}">Obriši izlozbu</a>
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
            {/section}
        </div>
        <h3>
            Ukupni broj izložbi iznosi: {$smarty.section.i.total}
        </h3>
    {/if}
</section>
