<link rel="stylesheet" href="{$putanja}CSS/prikaz_teblice.css"/>
<link rel="stylesheet" href="{$putanja}CSS/prikaz_forme.css"/>
<link rel="stylesheet" href="{$putanja}CSS/prikaz_izlozbe.css"/>
<!-- linkovi za js -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="{$putanja}javascript/vlakoviAPI.js"></script>
<section>
    <script src="{$putanja}javascript/tablica.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

    <h2>
        Popis svih Vaših prijava vlakova na izložbu:
    </h2>
    <br>
    {if isset($prikaz_tablice)}
        <table id="myTable" class="prikazTablice" style="width: 95%">
            <thead>
                <tr>
                    <th>Redni broj</th>
                    <th colspan="3">Podaci korisnika</th>
                    <th>Naziv vlaka</th>
                    <th>Tematika</th>
                    <th>Datum početka</th>
                    <th>Status prijave</th>
                    <th>Potvrda</th>
                </tr>
            </thead>
            <tbody>
                {section name=i loop=$prikaz_tablice}
                    {if $prikaz_tablice[i]->vazi_do >= $virtualni_datum OR !$prikaz_tablice[i]->vazi_do}
                        <tr 
                            {if $prikaz_tablice[i]->status == 'Na čekanju'}
                                style="background-color: #93B080"
                            {elseif $prikaz_tablice[i]->status == 'Odbijena'}
                                style="background-color: #D68B6F"
                            {/if}
                            >
                            <td>{$smarty.section.i.index_next}</td>
                            <td>{$prikaz_tablice[i]->ime_korisnika}</td>
                            <td>{$prikaz_tablice[i]->prezime_korisnika}</td>
                            <td>{$prikaz_tablice[i]->korisnicko_ime}</td>
                            <td>{$prikaz_tablice[i]->naziv}</td>
                            <td>{$prikaz_tablice[i]->naziv_tematike}</td>
                            <td>{$prikaz_tablice[i]->datum_pocetka_izlozbe}</td>
                            <td>{$prikaz_tablice[i]->status}</td>
                            <td>
                                {if $prikaz_tablice[i]->status == 'Potvrđena'}
                                    <a class="dodaj2" href="{$putanja}vlakovi/odbij_korisnika_na_izlozbu/{$prikaz_tablice[i]->id}">Odbij</a>
                                {elseif $prikaz_tablice[i]->status == 'Odbijena'}
                                    <a class="dodaj2" href="{$putanja}vlakovi/prihvati_korisnika_na_izlozbu/{$prikaz_tablice[i]->id}">Prihvati</a>
                                {else}
                                    <a class="dodaj2" href="{$putanja}vlakovi/prihvati_korisnika_na_izlozbu/{$prikaz_tablice[i]->id}">Prihvati</a>
                                    <a class="dodaj2" href="{$putanja}vlakovi/odbij_korisnika_na_izlozbu/{$prikaz_tablice[i]->id}">Odbij</a>
                                {/if}
                            </td>
                        </tr>
                    {/if}
                {/section}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8">Ukupno tematika vlakova:</td>
                    <td>{$smarty.section.i.total}</td>
                </tr>
            </tfoot>
        </table>
    {/if}
</section>
