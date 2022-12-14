function dalje() {
    document.getElementById("informacijaVlaka").style.display = "none";
    document.getElementById("informacijeNovogPogona").style.display = "block";
    document.getElementById("natrag").style.display = "block";
    document.getElementById("noviPogon").style.display = "none";
    document.getElementById("vrstaPognona").value = 1;
    document.getElementById("noviNazivPogona").value = "";
    document.getElementById("noviOpisPogona").value = "";
    
}
function unazad() {
    document.getElementById("informacijaVlaka").style.display = "block";
    document.getElementById("informacijeNovogPogona").style.display = "none";
    document.getElementById("noviPogon").style.display = "block";
    document.getElementById("vrstaPognona").value = "0";
    document.getElementById("noviOpisPogona").value = "nijePopunjeno";
    document.getElementById("noviNazivPogona").value = "nijePopunjeno";
    document.getElementById("natrag").style.display = "none";
}
