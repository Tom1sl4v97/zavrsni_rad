<link rel="stylesheet" href="{$putanja}CSS/prikaz_galerije.css"/>

<style>
    .prikazTeksta{
        padding: 0 0 0 30px;
    }
    .resetka {
        display: grid;
        grid-template-columns: auto auto auto;
    }
    section{
        font-size: 20px;
    }

    @media only screen and (min-width: 0px) and (max-width: 1300px){
        .resetka {
            display: grid;
            grid-template-columns: auto;
        }
    }
</style>

<section>
    {if isset($informacije_vlaka_korisnika)}
        <h2 class="naslovPravi">Informacije o prijavi vlaka</h2>
        <br><br>
        <div class="prikazTeksta, resetka">
            <div width="33%">
                <h2 style="padding-bottom: 10px">
                    <b>Korisnik:</b>
                </h2>
                <div class="prikazTeksta">
                    <b style="padding-right: 108px">Ime: </b>{$informacije_vlaka_korisnika->ime_korisnika}<br><br>
                    <b style="padding-right: 67px">Prezime:</b> {$informacije_vlaka_korisnika->prezime_korisnika}<br><br>
                    <b>Korisnicko ime:</b> {$informacije_vlaka_korisnika->korisnicko_ime}<br><br>
                    <address><b style="padding-right: 88px">E-mail:</b><a href="mailto:{$informacije_vlaka_korisnika->email}" style="text-decoration: none;">{$informacije_vlaka_korisnika->email}</a></address><br><br>
                </div>
            </div>
            <div width="33%">
                <h2 style="padding-bottom: 10px">
                    <b>Vlak:</b>
                </h2>
                <div class="prikazTeksta">
                    <b style="padding-right: 89px">Ime vlaka:</b> {$informacije_vlaka_korisnika->naziv}<br><br>
                    <b>Maksimalna brzina:</b> {$informacije_vlaka_korisnika->max_brzina} km/h<br><br>
                    <b style="padding-right: 66px">Broj sjedala:</b> {$informacije_vlaka_korisnika->broj_sjedala}<br><br>
                    <b>Opis vlaka:</b><br> {$informacije_vlaka_korisnika->opis}<br><br>
                </div>
            </div>
            <div width="33%">
                <h2 style="padding-bottom: 10px">
                    <b>Pogon:</b>
                </h2>
                <div class="prikazTeksta">
                    <b>Naziv pogona:</b> {$informacije_vlaka_korisnika->naziv_pogona}<br><br>
                    <b>Opis pogona:</b><br> {$informacije_vlaka_korisnika->opis_pogona}<br><br>
                </div>
            </div>
        </div>
        <br><br>
        {if !empty($slike_korisnika)}
            <h3>
                Galerija slika korisnika
            </h3>
            <div class="mjestoKutije">
                {foreach from=$slike_korisnika key=key item=val name=foo}
                    {if ($smarty.foreach.foo.index % 4) == 0 AND $smarty.foreach.foo.index != 0}
                    </div>
                    <div class="mjestoKutije">
                        <div class="kutija">
                            <img src="{$val->url}">
                            <p>{$naziv_slike[$key]}</p>
                        </div>
                    {else}
                        <div class="kutija">
                            <img src="{$val->url}">
                            <p>{$naziv_slike[$key]}</p>
                        </div>
                    {/if}
                {/foreach}
            </div>
            <br>
        {/if}

        {if !empty($video_korisnika)}
            <h3>
                Galerija videa korisnika
            </h3>
            <div class="mjestoKutije">
                {foreach from=$video_korisnika key=key item=val name=foo}
                    {if ($smarty.foreach.foo.index % 4) == 0 AND $smarty.foreach.foo.index != 0}
                    </div>
                    <div class="mjestoKutije">
                        <div class="kutija">
                            <video controls><source src="{$val->url}" type="video/mp4" class="video"></video>
                            <p>{$naziv_videa[$key]}</p>
                        </div>
                    {else}
                        <div class="kutija">
                            <video controls><source src="{$val->url}" type="video/mp4" class="video"></video>
                            <p>{$naziv_videa[$key]}</p>
                        </div>
                    {/if}
                {/foreach}
            </div>
        {/if}
        {if !empty($audio_korisnika)}
            <h3>
                Galerija audia korisnika
            </h3>
            <div class="mjestoKutije" style="height: 200px">
                {foreach from=$audio_korisnika key=key item=val name=foo}
                    {if ($smarty.foreach.foo.index % 4) == 0 AND $smarty.foreach.foo.index != 0}
                    </div>
                    <div class="mjestoKutije" style="height: 200px">
                        <div class="kutija">
                            <audio controls><source src="{$val->url}" type="video/mp4" class="video"></audio>
                            <p>{$naziv_audia[$key]}</p>
                        </div>
                    {else}
                        <div class="kutija">
                            <audio controls><source src="{$val->url}" type="video/mp4" class="video"></audio>
                            <p>{$naziv_audia[$key]}</p>
                        </div>
                    {/if}
                {/foreach}
            </div>
        {/if}
        {if !empty($gif_korisnika)}
            <h3>
                Galerija gifova korisnika
            </h3>
            <div class="mjestoKutije">
                {foreach from=$gif_korisnika key=key item=val name=foo}
                    {if ($smarty.foreach.foo.index % 4) == 0 AND $smarty.foreach.foo.index != 0}
                    </div>
                    <div class="mjestoKutije" style="height: 200px">
                        <div class="kutija">
                            <img src="{$val->url}"/>
                            <p>{$naziv_gifa[$key]}</p>
                        </div>
                    {else}
                        <div class="kutija">
                            <img src="{$val->url}"/>
                            <p>{$naziv_gifa[$key]}</p>
                        </div>
                    {/if}
                {/foreach}
            </div>
        {/if}


    {/if}
</section>
