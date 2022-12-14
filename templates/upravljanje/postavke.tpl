<link rel="stylesheet" href="{$putanja}CSS/prikaz_izlozbe.css"/>
<link rel="stylesheet" href="{$putanja}CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}CSS/prikaz_teblice.css"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="{$putanja}javascript/tablica.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<section>
    <h2>
        Prikaz postavki stranice
    </h2>
    <br>
    <div class="resetka">
        <div style="margin-bottom: 25px; min-width: 100%">
            <div class="izlozbaVlakova">
                <div class="naslov">
                    <p>
                        Izmjena trajanja sesije
                    </p>
                </div>
                <div>
                    <div class="podkategorije">
                        <form class="prikazForme" method="post" name="promjenaTrajanjaSesije" action="{$putanja}upravljanje/azuriraj_trajanje_sesije/">
                            <p>
                                Trenutno: {$trenutni_zapis_sesije} h
                            </p>
                            <input style="width: 97%; height: 25px" type="time" step="1" name="izmjeniSesiju"><br><br>
                            <input class="dodaj" type="submit" value="Spremi">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 25px; min-width: 100%">
            <div class="izlozbaVlakova">
                <div class="naslov">
                    <p>
                        Izmjena virtualnog vrijemena<br>(sati)
                    </p>
                </div>
                <div>
                    <div class="podkategorije">
                        <form class="prikazForme" method="post" name="promjenaTrajanjaSesije" action="{$putanja}upravljanje/azuriraj_virtualno_vrijeme/">
                            <p>
                                Trenutno: {$trenutni_zapis_virtualnog_vremena} h razlike
                            </p>
                            <b><a target="_blank" href="http://barka.foi.hr/WebDiP/pomak_vremena/vrijeme.html" 
                                  style="text-decoration: none;">
                                    Postavi virtualno vrijeme
                                </a>
                            </b>
                            <br><br>
                            <input class="dodaj" type="submit" value="Spremi">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 25px; min-width: 100%">
            <div class="izlozbaVlakova">
                <div class="naslov">
                    <p>
                        Izmjena trajanja kolacica<br>(dani)
                    </p>
                </div>
                <div>
                    <div class="podkategorije">
                        <form class="prikazForme" method="post" name="promjenaTrajanjaKolacica" 
                              action="{$putanja}upravljanje/azuriraj_trajanje_kolacica/">
                            <p>
                                Trenutno: {$trenutni_zapis_kolacica} dan/a
                            </p>
                            <input style="width: 97%; height: 25px" type="text" name="novoTrajanjeKolacica"><br><br>
                            <input class="dodaj" type="submit" value="Spremi">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 25px; min-width: 100%">
            <div class="izlozbaVlakova">
                <div class="naslov">
                    <p>
                        Resetiranje uvjeta korištenja<br>
                        svih korisnika (dani)
                    </p>
                </div>
                <div>
                    <div class="podkategorije">
                        <form class="prikazForme" method="post" action="{$putanja}upravljanje/resetiraj_uvjete_koristenja/">
                            <input class="dodaj" type="submit" value="Resetiraj uvjete" style="margin-top: 10px">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 25px; min-width: 100%">
            <div class="izlozbaVlakova">
                <div class="naslov">
                    <p>
                        Napravi sigurosnu kopiju<br>svih vlakova i materijala
                    </p>
                </div>
                <div>
                    <div class="podkategorije">
                        <form class="prikazForme" method="post" action="{$putanja}upravljanje/kreiraj_sigurnosnu_kopiju/">
                            <input class="dodaj" type="submit" value="Napravi sigurosnu kopiju" style="margin-top: 10px">
                        </form>
                    </div>
                    <br><br>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 25px; min-width: 100%">
            <div class="izlozbaVlakova">
                <div class="naslov">
                    <p>
                        Postavi podatke iz sigurosne<br>kopije svi vlakova i materijala
                    </p>
                </div>
                <div>
                    <div class="podkategorije">
                        <form class="prikazForme" method="post" action="{$putanja}upravljanje/vrati_sigurnosnu_kopiju/">
                            <input class="dodaj" type="submit" value="Postavi podatke iz kopije" style="margin-top: 10px">
                        </form>
                    </div>
                    <br><br>
                </div>
            </div>
        </div>
    </div>

    {if isset($popis_korisnika)}

        <br><br>
        <h2>
            Lista korisničkih računa
        </h2>
        <table id="myTable" class="prikazTablice" style="width: 95%;">
            <thead>
                <tr>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Korisničko ime</th>
                    <th>E-mail</th>
                    <th>Uloga</th>
                    <th>Gumb</th>
                </tr>
            </thead>
            {section name=i loop=$popis_korisnika}
                <tr>
                    <td>{$popis_korisnika[i]->ime}</td>
                    <td>{$popis_korisnika[i]->prezime}</td>
                    <td>{$popis_korisnika[i]->korisnicko_ime}</td>
                    <td>{$popis_korisnika[i]->email}</td>
                    <td>{$popis_korisnika[i]->naziv_uloge}</td>
                    <td>

                        {if $popis_korisnika[i]->broj_neuspijesnih_prijava < 3}
                            <a class="dodaj" href="{$putanja}upravljanje/blokiraj_korisnika/{$popis_korisnika[i]->id}/" 
                               style="background-color: #D68B6F;color: black">Blokiraj</a>
                        {else}
                            <a class="dodaj" href="{$putanja}upravljanje/de_blokiraj_korisnika/{$popis_korisnika[i]->id}/" 
                               style="background-color: #93B080;color:black">Prihvati</a>
                        {/if}
                    </td>
                </tr>
            {/section}
            <tfoot>
                <tr>
                    <td colspan="4">Ukupno tematika vlakova:</td>
                    <td colspan="2">{$smarty.section.i.total}</td>
                </tr>
            </tfoot>
        </table>
    {/if}

    <br><br>
    <h2>
        Zapis dnevnika korištenja
    </h2>
    <div class="podkategorije">
        <form class="prikazForme" method="post" action="{$putanja}upravljanje/prikazi_postavke_stranice/">
            <label for="pocetniDatum">Pretraži dnevnik od datuma:</label><br>
            <input type="date" id="pocetniDatum" name="pocetniDatum" class="okvirForme2" style="width: 200px; height: 4px"><br><br>

            <label for="zavrsniDatum">Pretraži dnevnik do datuma:</label><br>
            <input type="date" id="zavrsniDatum" name="zavrsniDatum" class="okvirForme2" style="width: 200px; height: 4px"><br><br>

            <input class="gumbPrijava" type="submit" value="Pretraži"><br><br>
        </form>
    </div>

    <table id="myTable2" class="prikazTablice" style="width: 95%;">
        <thead>
            <tr>
                <th>ID dnevnika</th>
                <th>Korisnik</th>
                <th>E-mail</th>
                <th>Stranica</th>
                <th>Upit</th>
                <th>Datum</th>
                <th>Tip radnje</th>
            </tr>
        </thead>

        {section name=i loop=$dnevnik_koristenja_stranice}
            <tr>
                <td>{$dnevnik_koristenja_stranice[i]->id}</td>
                <td>{$dnevnik_koristenja_stranice[i]->ime} {$dnevnik_koristenja_stranice[i]->prezime} {$dnevnik_koristenja_stranice[i]->korisnicko_ime}</td>
                <td>{$dnevnik_koristenja_stranice[i]->email}</td>
                <td>{$urlStranica[i]}</td>
                <td>{$dnevnik_koristenja_stranice[i]->upit}</td>
                <td>{$dnevnik_koristenja_stranice[i]->datum_pristupa}</td>
                <td>{$dnevnik_koristenja_stranice[i]->tip_dnevnika_opis}</td>
            </tr>
        {/section}
        <tfoot>
            <tr>
                <td colspan="6">Ukupan broj zapisa:</td>
                <td>{$smarty.section.i.total}</td>
            </tr>
        </tfoot>
    </table>

</section>
