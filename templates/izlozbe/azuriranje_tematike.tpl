<link rel="stylesheet" href="{$putanja}/CSS/prikaz_forme.css"/>
<section id="sadrzajObrasca">
    <div>
        <form id ="form1" method="post" name="dodavanjeNoveTematike" action="{$putanja}izlozbe/azuriraj_tematiku/{$id}">
            <br>
            
            <div id="greske" class="greska">
                {if isset($greska)}
                    <a>{$greska}</a> <br>
                {/if}
            </div>

            <label for="nazivTematike">Naziv tematike: </label>
            <input class="okvirForme" type="text" id="nazivTematike" name="nazivTematike"
                   {if isset($naziv_tematike)}
                       value="{$naziv_tematike}"
                   {/if}>
            
            <br><br>
            
            <label for="OpisTematike">Opis tematike: </label>
            <input class="okvirForme" type="text" id="opisTematike" name="opisTematike"
                   {if isset($opis_tematike)}
                       value="{$opis_tematike}"
                   {/if}>
            
            <br><br>

            <input class="gumbPrijava" type="submit" name="dodajTematiku" value="Spremi">
            <a class="gumbPrijava" href="{$putanja}izlozbe/prikaz_administracija_izlozba/">Odustani</a>
            
            <br><br>
        </form>
    </div>
</section>
