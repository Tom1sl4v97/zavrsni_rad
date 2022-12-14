function popUpUpitnik() {
    popUpPomoc = document.getElementById("kutijaDizajnaPomoci");
    popUpPomoc.addEventListener("click", function (event) {
        popUpPomoc.style.display = "none";
        var popUp = document.getElementById("popUpPomoc");
        popUp.style.display = "block";
        var ne = document.getElementById("pomocNe1");
        ne.addEventListener("click", function (event) {
            popUp.style.display = "none";
            popUpPomoc.style.display = "block";
        }, false);
        var da = document.getElementById("pomocDa1");
        da.addEventListener("click", function (event) {
            popUp.style.display = "none";
            document.getElementById("pravokutnik1").style.display = "block";
            pomocPravokutnik1();
        }, false);
    }, false);

}

function pomocPravokutnik1() {
    var pravokutnik = document.getElementById("pravokutnik1");
    pravokutnik.addEventListener("click", function (event) {
        document.getElementById("pravokutnik1").style.display = "none";
        document.getElementById("pravokutnik2").style.display = "block";
        pomocPravokutnik2();
    }, false);
}
function pomocPravokutnik2() {
    var pravokutnik = document.getElementById("pravokutnik2");
    pravokutnik.addEventListener("click", function (event) {
        document.getElementById("pravokutnik2").style.display = "none";
        document.getElementById("pravokutnik3").style.display = "block";
        pomocPravokutnik3();
    }, false);
}
function pomocPravokutnik3() {
    var pravokutnik = document.getElementById("pravokutnik3");
    pravokutnik.addEventListener("click", function (event) {
        document.getElementById("pravokutnik3").style.display = "none";
        document.getElementById("pravokutnik4").style.display = "block";
        pomocPravokutnik4();
    }, false);
}
function pomocPravokutnik4() {
    var pravokutnik = document.getElementById("pravokutnik4");
    pravokutnik.addEventListener("click", function (event) {
        document.getElementById("pravokutnik4").style.display = "none";
        document.getElementById("pravokutnik5").style.display = "block";
        pomocPravokutnik5();
    }, false);
}
function pomocPravokutnik5() {
    var pravokutnik = document.getElementById("pravokutnik5");
    pravokutnik.addEventListener("click", function (event) {
        document.getElementById("pravokutnik5").style.display = "none";
        document.getElementById("pravokutnik6").style.display = "block";
        pomocPravokutnik6();
    }, false);
}
function pomocPravokutnik6() {
    var pravokutnik = document.getElementById("pravokutnik6");
    pravokutnik.addEventListener("click", function (event) {
        document.getElementById("pravokutnik6").style.display = "none";
        document.getElementById("pravokutnik7").style.display = "block";
        pomocPravokutnik7();
    }, false);
}
function pomocPravokutnik7() {
    var pravokutnik = document.getElementById("pravokutnik7");
    pravokutnik.addEventListener("click", function (event) {
        document.getElementById("pravokutnik7").style.display = "none";
        popUpPomoc = document.getElementById("kutijaDizajnaPomoci").style.display = "block";
    }, false);
}