<link rel="stylesheet" href="{$putanja}CSS/prikaz_forme.css"/>
<section id="sadrzajObrasca">
    <div>
        <br>
        <form id ="form1" method="post" name="dodavanjeNoveTematike" action="{$putanja}izlozbe/azuriraj_izlozbu/{$id}">
            <br>

            <label for="odabirModeratoraTematike">Odaberite željenu temu izložbe vlaka:</label><br>
            <select name="odabirModeratoraTematike" id="odabirModeratoraTematike" class="prikazDropDown">
                <option value="0" class="prikazDropDown">Odaberite</option>
                {section name=i loop=$popis_teme_izlozbe}
                    <option value="{$popis_teme_izlozbe[i]->tematika_id}" class="prikazDropDown" 
                            {if isset($uredi_temu)}
                                {if $uredi_temu == $popis_teme_izlozbe[i]->tematika_id}}
                                    selected
                                {/if}
                            {/if}>
                        {$popis_teme_izlozbe[i]->naziv_tematike}
                    </option>
                {/section}
            </select><br><br>
            <label for="datumPocetka">Unesite datum početka izložbe: </label>
            <input class="okvirForme2" type="datetime-local" id="datumPocetka" name="datumPocetka"
                   {if isset($uredi_datum)}
                       value="{$uredi_datum[0]}T{$uredi_datum[1]}"
                   {/if}>
            <br><br>
            <label for="maxBrojKorisnika">Maksimalan broj korisnika: </label>
            <input class="okvirForme2" type="number" id="maxBrojKorisnika" name="maxBrojKorisnika"
                   {if isset($uredi_max_korisnika)}
                       value="{$uredi_max_korisnika}"
                   {/if}
                   ><br><br>

            <label for="pocetakGlasovanja">Početak glasovanja</label><br>
            <input type="date" id="datum" name="pocetakGlasovanja" class="okvirForme2"
                   {if !empty($id)}
                       value="{$uredi_Datum->vazi_od|date_format:"Y-m-d"}"
                   {/if}
                   ><br><br>
            <label for="zavrsetakGlasovanja">Završetak glasovanje</label><br>
            <input type="date" id="datum" name="zavrsetakGlasovanja" class="okvirForme2"
                   {if !empty($id)}
                       value="{$uredi_Datum->vazi_do|date_format:"Y-m-d"}"
                   {/if}
                   ><br><br>


            <input class="gumbPrijava" type="submit" name="dodajModeratoraTematike" value="Dodaj izložbu">
            <a class="gumbPrijava" href="{$putanja}izlozbe/prikaz_uredivanje_izlozba/">Odustani</a>
            <br><br>
        </form>
        <br>
    </div>
</section>
