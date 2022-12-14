window.addEventListener("load", zatvoriPopUp);

function zatvoriPopUp() {
    odustani = document.getElementById("odustani");
    if (odustani) {
        odustani.addEventListener("click", function (event) {
            document.getElementById("popUpPrihvaćanjaUvjetaKoristenja").style.display = "none";
        }, false);
    }
}