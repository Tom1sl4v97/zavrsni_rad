<link rel="stylesheet" href="{$putanja}CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}CSS/popUp_prozor.css"/>
<link rel="stylesheet" href="{$putanja}CSS/popUp_pomoc_autor.css"/>

<script src="{$putanja}javascript/prikaz_pomoci.js"></script>

<section>

    <div id="kutijaDizajnaPomoci">
        <button type="submit" class="slikaGumbaPomoci" onclick="popUpUpitnik()"></button>
    </div>
    
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
        <br>Ovo je navigacija
    </div>
    <div id="pravokutnik2" class="pravokutnik2">
        Prikaz informacija o autoru
    </div>
    <div id="pravokutnik3" class="pravokutnik3">
        Možete kontaktirati autora <br> sa gumbom "kontaktiraj me"
    </div>
    <div id="pravokutnik4" class="pravokutnik4">
        Klikom na ime autora, <br> posjecujete stranicu o autoru
    </div>
    <div id="pravokutnik5" class="pravokutnik5">
        Ako ste se ulogirali možete promjeniti temu stranice na tamnu
    </div>
    <div id="pravokutnik6" class="pravokutnik6">
        Ako ste se ulogirali možete promjeniti temu stranice sa prilogodbom za dislekciju
    </div>
    <div id="pravokutnik7" class="pravokutnik7">
        Ponovnik <br>klikom<br> vraćate<br> postavke<br> stranice
    </div>

    <h2>Informacije o autoru:</h2>
    <div style="background-color: #FEFFE4;">
        <img src="{$putanja}/multimedija/Tomislav.jpeg" alt="Tomislav Tomiek" width="250" style="float: left;margin:0px 10% 20px 5%;"/>

        <a>Prezime autora: Tomiek</a><br><br>
        <a>Ime autora: Tomislav</a><br><br>
        <a>Broj indexa: 45999 </a><br><br>
        <address>e-mail <a href="mailto:ttomiek@foi.hr" style="font-weight: bold;">kontaktiraj me</a></address>
    </div>
    <br><br><br><br><br><br><br><br>

</section>
