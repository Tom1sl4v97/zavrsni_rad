<link rel="stylesheet" href="{$putanja}CSS/prikaz_forme.css"/>
<section id="sadrzajObrasca">
    <div>
        <br>
        <form id ="form1" method="post" name="dodavanjeNoveTematike" action="{$putanja}izlozbe/azuriraj_dodijelu_moderatora/{$id}">
            <br>

            <label for="odabirModeratoraTematike">Odaberite moderatora za tematiku vlakova:</label><br>
            <select name="odabirModeratoraTematike" id="odabirModeratoraTematike" class="prikazDropDown">
                <option value="0" class="prikazDropDown">Odaberite</option>
                {section name=i loop=$popis_moderatora}
                    <option value="{$popis_moderatora[i]->id}" class="prikazDropDown"
                            {if isset($podaci_uredivanja_moderatora_tematike.id_moderator_tematike_vlakova)}
                                {if $podaci_uredivanja_moderatora_tematike.id_moderator_tematike_vlakova == $popis_moderatora[i]->id}}
                                    selected
                                {/if}
                            {/if}
                            >
                        {$popis_moderatora[i]->ime} {$popis_moderatora[i]->prezime} - {$popis_moderatora[i]->korisnicko_ime}
                    </option>
                {/section}
            </select><br><br>

            <label for="odabirTematike">Odaberite postojeću tematiku vlakova:</label><br>
            <select name="odabirTematike" id="odabirTeamtike" class="prikazDropDown">
                <option value="0" class="prikazDropDown">Odaberite</option>
                {section name=i loop=$popis_tematike_vlakova}
                    <option value="{$popis_tematike_vlakova[i]->id}" class="prikazDropDown"
                            {if isset($podaci_uredivanja_moderatora_tematike.id_tematika_vlakova)}
                                {if $podaci_uredivanja_moderatora_tematike.id_tematika_vlakova == $popis_tematike_vlakova[i]->id}}
                                    selected
                                {/if}
                            {/if}
                            >{$popis_tematike_vlakova[i]->naziv}</option>
                {/section}
            </select><br><br>

            <label for="datumOd">Unesite datum od kada vrijedi zadani moderator:</label><br>
            <input type="date" id="datum" name="datumOd" class="okvirForme2"
                   {if isset($podaci_uredivanja_moderatora_tematike.vrijedi_od)}
                       value="{$podaci_uredivanja_moderatora_tematike.vrijedi_od}"
                   {/if}
                   ><br><br>

            <label for="datumDo">Unesite datum do kada vrijedi zadani moderator:</label><br>
            <input type="date" id="datum" name="datumDo" class="okvirForme2"
                   {if isset($podaci_uredivanja_moderatora_tematike.vrijedi_do)}
                       value="{$podaci_uredivanja_moderatora_tematike.vrijedi_do}"
                   {/if}
                   >
            <br><br>

            <input class="gumbPrijava" type="submit" name="spremi" value="Spremi">
            <a class="gumbPrijava" href="{$putanja}izlozbe/prikaz_administracija_izlozba/">Odustani</a>
            <br><br>
        </form>
        <br>
    </div>
</section>
