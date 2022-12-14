window.addEventListener("load", zatvori_pop_up);

function odabir_vlaka(naziv) {
    dodaj = document.getElementById("dodaj_vlak");
    dodaj.style.display = "block";
    document.getElementById("textIzlozbe").innerHTML = naziv;
}

function dodaj_materijale(index) {
    prikaz_dodavanja_materijala = document.getElementById("dodaj_materijale" + index);
    prikaz_dodavanja_materijala.style.display = "block";

}

function zatvori_pop_up_materijala(index) {
    prikaz_dodavanja_materijala = document.getElementById("dodaj_materijale" + index);
    prikaz_dodavanja_materijala.style.display = "none";
}

function otvori_glasovanje(index) {
    prikaz_dodavanja_materijala = document.getElementById("glasaj" + index);
    prikaz_dodavanja_materijala.style.display = "block";
}

function zatvori_pop_up_glasovanja(index) {
    prikaz_dodavanja_materijala = document.getElementById("glasaj" + index);
    prikaz_dodavanja_materijala.style.display = "none";
}

function zatvori_pop_up() {
    odustani_prijaviti_vlak = document.getElementById("odustani");
    if (odustani_prijaviti_vlak) {
        odustani_prijaviti_vlak.addEventListener("click", function (event) {
            document.getElementById("dodaj_vlak").style.display = "none";
        }, false);
    }
}