<link rel="stylesheet" href="{$putanja}CSS/prikaz_teblice.css"/>
<link rel="stylesheet" href="{$putanja}CSS/prikaz_forme.css"/>
<!-- linkovi za js -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="{$putanja}javascript/vlakoviAPI.js"></script>
<section>
    <script src="{$putanja}javascript/tablica.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>


    <h2>
        Tematike vlakova:
        <a class="gumbPrijava2" style="float: right" href="{$putanja}izlozbe/prikaz_podataka_tematike/">Dodaj novu tematiku</a>
    </h2>
    <table id="myTable" class="prikazTablice" style="width: 95%;">
        <thead>
            <tr>
                <th>Naziv tematike</th>
                <th>Opis tematike</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        {if isset($lista_tematike_vlakova)}
            <tbody>
                {section name=brojac_liste_tematike loop=$lista_tematike_vlakova}
                    <tr>
                        <td>{$lista_tematike_vlakova[brojac_liste_tematike]->naziv}</td>
                        <td>{$lista_tematike_vlakova[brojac_liste_tematike]->opis}</td>
                        <td>
                            <a class="prikazGumbicaUredivanja" href="{$putanja}izlozbe/prikaz_administracija_izlozba/{$lista_tematike_vlakova[brojac_liste_tematike]->id}">Detalji</a>
                        </td> 
                        <td>
                            <a class="prikazGumbicaUredivanja" href="{$putanja}izlozbe/prikaz_podataka_tematike/{$lista_tematike_vlakova[brojac_liste_tematike]->id}">Uredi</a>
                        </td>
                        <td>
                            <a class="prikazGumbicaUredivanja" href="{$putanja}izlozbe/obrisi_tematiku/{$lista_tematike_vlakova[brojac_liste_tematike]->id}/">Obrisi</a>
                        </td>
                    </tr>
                {/section}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Ukupno tematika vlakova:</td>
                    <td colspan="2">{$smarty.section.i.total}</td>
                </tr>
            </tfoot>
        </table>
    {/if}

    {if isset($detalji_tematike)}
        <div id="detaljiZapisa">
            <br><br>
            <table class="prikazTablice" style="width: 95%">
                <caption style="font-size: 24px; padding: 0px 0px 15px 0px;">Detalji odabrane tematike:</caption>
                <thead>
                    <tr>
                        <th>Naziv tematike</th>
                        <th>Opis tematike</th>
                        <th>Kreirao korisnik</th>
                        <th>Datum kreiranja</th>
                        <th>Ažurirao korisnik</th>
                        <th>Datum ažuriranja</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{$detalji_tematike->naziv}</td>
                        <td>{$detalji_tematike->opis}</td>
                        <td>{$detalji_tematike->korisnik_kreiranja}</td>
                        <td>{$detalji_tematike->datum_kreiranja}</td>
                        <td>{$detalji_tematike->korisnik_azuriranja}</td>
                        <td>{$detalji_tematike->datum_azuriranja}</td>
                    </tr>
                </tbody>
            </table>
            <br><br>
        </div>
    {/if}
    <br><br>
    <h2>
        <a>Moderatori</a>
        <a class="gumbPrijava2" style="float: right" href="{$putanja}izlozbe/prikaz_dodijele_moderatora/">Dodijeli novog moderatora</a>
    </h2>
    {if isset($popis_moderatora)}
        <table id="myTable2" class="prikazTablice" style="width: 95%;">
            <thead>
                <tr>
                    <th>Administrator</th>
                    <th>Moderator</th>
                    <th>Tematika</th>
                    <th>Vazi od</th>
                    <th>Vazi do</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {section name=brojac_popisa_moderatora loop=$popis_moderatora}
                    <tr>
                        <td>{$popis_moderatora[brojac_popisa_moderatora]->korisnicko_ime_administratora}</td>
                        <td>{$popis_moderatora[brojac_popisa_moderatora]->korisnicko_ime_moderatora}</td>
                        <td>{$popis_moderatora[brojac_popisa_moderatora]->naziv_tematike}</td>
                        <td>{$popis_moderatora[brojac_popisa_moderatora]->vazi_od|date_format:"d.m.Y."}</td>
                        <td>
                            {if $popis_moderatora[brojac_popisa_moderatora]->vazi_do != "NULL"}
                                {$popis_moderatora[brojac_popisa_moderatora]->vazi_do|date_format:"d.m.Y."}
                            {/if}
                        </td>
                        <td>
                            <a class="prikazGumbicaUredivanja" href="{$putanja}izlozbe/prikaz_dodijele_moderatora/{$popis_moderatora[brojac_popisa_moderatora]->id}">Uredi</a>
                        </td>
                        <td>
                            <a class="prikazGumbicaUredivanja" href="{$putanja}izlozbe/obrisi_zapis_tablice_moderatora/{$popis_moderatora[brojac_popisa_moderatora]->id}">Obrisi</a>
                        </td>
                    </tr>
                {/section}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Ukupni broj moderatora tematike vlakova:</td>
                    <td colspan="3">{$smarty.section.brojac_popisa_moderatora.total}</td>
                </tr>
            </tfoot>
        </table>
    {/if}

</section>
