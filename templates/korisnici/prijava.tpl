<link rel="stylesheet" href="{$putanja}CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}CSS/popUp_prozor.css"/>
<link rel="stylesheet" href="{$putanja}CSS/popUp_pomoc_prijave.css"/>

<script src="{$putanja}javascript/prikaz_pomoci.js"></script>

<section id="sadrzajObrasca">

    <div id="kutijaDizajnaPomoci">
        <button type="submit" class="slikaGumbaPomoci" onclick="popUpUpitnik()"></button>
    </div>

    <div>
        <div class="popUp" id="popUpPomoc">
            <div class="popUpPozadina">
                Trebate li pomoć?<br><br>
                <div id="pomocDa1" style="float: left;width: 100px;">
                    Da
                </div>
                <div id="pomocNe1" style="float: left;width: 100px">
                    Ne
                </div>
                <br>
            </div>
        </div> 
        <div id="pravokutnik1" class="pravokutnik1">
            Ovo je navigacija
        </div>
        <div id="pravokutnik2" class="pravokutnik2">
            Ovdje se vrši unos podataka.
        </div>
        <div id="pravokutnik3" class="pravokutnik3">
            Unos postojećeg korisničkog imena.
        </div>
        <div id="pravokutnik4" class="pravokutnik4">
            Unos lozinke korisnika.
        </div>
        <div id="pravokutnik5" class="pravokutnik5">
            Zapamćuje korisničko ime korisnika.
        </div>
        <div id="pravokutnik6" class="pravokutnik6">
            Ako korisnik ne posijeduje račun,<br> mora kliknuti na "nemate račun?"
        </div>
        <div id="pravokutnik7" class="pravokutnik7">
            Kada ste sve popunili, morate<br>kliknuti na gumb "Prijavi se" 
        </div>

        <form novalidate method="post" action="{$putanja}korisnici/prijava_korisnika/">
            <br>
            <label for="korime">Korisničko ime: </label>
            <input class="okvirForme" type="text" id="korime" name="korime"
                   {if isset($korisnicko_ime)}
                       value="{$korisnicko_ime}"
                   {/if}>
            <br><br>
            <label for="lozinka">Lozinka: </label>
            <input class="okvirForme" type="password" id="lozinka" name="lozinka">
            <br><br>
            <a class="gumbPrijava" href="{$putanja}korisnici/prijava_tesnog_korisnika/3">Registrirani korisnik</a>
            <a class="gumbPrijava" href="{$putanja}korisnici/prijava_tesnog_korisnika/2">Moderator</a>
            <a class="gumbPrijava" href="{$putanja}korisnici/prijava_tesnog_korisnika/1">Administrator</a>
            <br><br>
            <label for="zapamtiMe"> Zapamti me</label>
            <input type="checkbox" id="zapamtiMe"  name="zapamtiMe" value="da" style="width: 18px;height: 18px" 
                   {if $korisnicko_ime}
                       checked
                   {/if}>
            <br><br>

            <a href="{$putanja}korisnici/prebaci_na_registraciju/" style="text-decoration: none;"> Nemate račun?</a>
            <br><br>
            <input class="gumbPrijava" type="submit" value="Prijavi se">
            <br><br>
        </form>
    </div>
</section>
