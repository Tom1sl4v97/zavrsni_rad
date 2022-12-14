<link rel="stylesheet" href="{$putanja}CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}CSS/popUp_prozor.css"/>
<link rel="stylesheet" href="{$putanja}CSS/slider_slike.css"/>
<link rel="stylesheet" href="{$putanja}CSS/prikaz_izlozbe.css"/>


<section>
    {if isset($lista_vlakova)}
        <a class="gumbPrijava" style="float: right;margin-right: 1%;text-decoration: none" href="{$putanja}vlakovi/prikaz_podataka_kod_azuriranja_vlak/">Dodaj novi vlak</a>
        <h2 style="padding-bottom: 10px">
            Prikaz vaših dodanih vlakova:
        </h2>


        <div class="resetka">
            {section name=i loop=$lista_vlakova}
                <div style="margin-bottom: 20px; max-width: 400px">
                    <div class="izlozbaVlakova">
                        <div class="naslov">
                            {$lista_vlakova[i]->naziv}
                        </div>
                        <div>
                            <div class="podkategorije">
                                <b>Opis vlaka:</b> <br><br> &nbsp;&nbsp; {$lista_vlakova[i]->opis}<br><br>
                                <b>Maximalna brzina vlaka: </b>{$lista_vlakova[i]->max_brzina} km/h<br><br>
                                <b>Ukupan broj sjedala: </b>{$lista_vlakova[i]->broj_sjedala}<br><br>
                                <b>Naziv pogona: </b>{$lista_vlakova[i]->naziv_pogona}
                            </div>
                            <div style="padding: 0 5px">
                                <a class="dodaj" href="{$putanja}vlakovi/prikaz_podataka_kod_azuriranja_vlak/{$lista_vlakova[i]->id}">Uredi vlak</a>
                                <a class="dodaj" href="{$putanja}vlakovi/obrisi_valk_korisnika/{$lista_vlakova[i]->id}">Obriši vlak</a>
                            </div>
                            {assign "provjera" "da"}
                            {section name=k loop=$slikaVlaka}
                                {if isset($slikaVlaka) AND $slikaVlaka[k].IDVlaka ==  $lista_vlakova[i]->id AND $provjera == 'da'}
                                    <div>
                                        <img src="{$putanja}/multimedija/vlak1.jpg" alt="{$slikaVlaka[k].url}" style="width: 100%">
                                    </div>
                                    {assign "provjera" "ne"}
                                {/if}
                            {/section}
                        </div>
                    </div>
                </div>
            {/section}
        </div>
    {else}
        <h2>
            Niste prijavili niti jedan vlak
            <form style="background-color: transparent; border: none; float: right;">
                <button type="submit" name="dodavanjeNovogVlaka" class="gumbPrijava" value="">Dodaj novi vlak</button>
            </form>
        </h2>
    {/if}
</section>
