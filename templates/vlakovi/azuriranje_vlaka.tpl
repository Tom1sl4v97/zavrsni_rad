<link rel="stylesheet" href="{$putanja}/CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}/CSS/prikaz_teblice.css"/>

<script src="{$putanja}/javascript/dodavanje_novog_pogona_vlaka.js" ></script>
<section id="sadrzajObrasca">
    <div>
        <br>
        <form id ="form1" method="post" name="dodavanjeNovogVlaka" action="{$putanja}vlakovi/azuriraj_vlak_korisnika/{$id}" enctype="multipart/from-data">
            
            <br>
            <div id="informacijaVlaka">
                <label for="nazivVlaka">Naziv vlaka: </label>
                <input class="okvirForme" type="text" id="nazivVlaka" name="nazivVlaka"
                       {if isset($podaci_vlaka)}
                           value="{$podaci_vlaka->naziv}"
                       {/if}
                       ><br><br>

                <label for="maxBrzina">Maksimalna brzina vlaka (km/h): </label>
                <input class="okvirForme" type="text" id="maxBrzina" name="maxBrzina" 
                       {if isset($podaci_vlaka)}
                           value="{$podaci_vlaka->max_brzina}"
                       {/if}
                       ><br><br>

                <label for="brojSjedala">Broj sjedala vlaka: </label>
                <input class="okvirForme" type="text" id="brojSjedala" name="brojSjedala" 
                       {if isset($podaci_vlaka)}
                           value="{$podaci_vlaka->broj_sjedala}"
                       {/if}
                       ><br><br>

                <label for="opisVlaka">Opis vlaka: </label>
                <input class="okvirForme" type="text" id="opisVlaka" name="opisVlaka" 
                       {if isset($podaci_vlaka)}
                           value="{$podaci_vlaka->opis}"
                       {/if}
                       ><br><br>

                <label for="vrstaPognona">Vrsta pogona:</label><br>
                <select name="vrstaPognona" id="vrstaPognona" class="prikazDropDown">
                    <option value="0" class="prikazDropDown">Odaberite</option>
                    {section name=i loop=$vrsta_pogona}
                        <option value="{$vrsta_pogona[i]->id}" class="prikazDropDown"
                                {if isset($podaci_vlaka) AND $podaci_vlaka->vrsta_pogona_id == $vrsta_pogona[i]->id}
                                    selected
                                {/if}
                                >
                            {$vrsta_pogona[i]->naziv_pogona}
                        </option>
                    {/section}
                </select>
                <br><br>
            </div>

            <div id="informacijeNovogPogona" style="display: none">

                <label for="noviNazivPogona">Naziv novog pogona: </label>
                <input class="okvirForme" type="text" id="noviNazivPogona" name="noviNazivPogona" value="nijePopunjeno"><br><br>

                <label for="noviOpisPogona">Opis novog pogona: </label>
                <input class="okvirForme" type="text" id="noviOpisPogona" name="noviOpisPogona" value="nijePopunjeno"><br><br>

            </div>

            <div class="gumbPrijava" style="float: right" onclick="dalje()" id="noviPogon">Želite li dodati novi pogon?</div>
            <div class="gumbPrijava" style="float: right;display: none;" id="natrag" onclick="unazad()">Vrati se</div>

            <input class="gumbPrijava" type="submit" name="dodavanjeNovogVlaka" value="Dodaj vlak">
            <a class="gumbPrijava" href="{$putanja}vlakovi/prikaz_vlakova_korisnika/">Odustani</a>
            </p>
        </form>
        <br>
    </div>
</section>
