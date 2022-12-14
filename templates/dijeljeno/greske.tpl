<section id="greske" >
    {section name=greska loop=$greske}
        <div class="prikazGreske"> {$greske[greska]}
            <button type="button" class="zatvoriBtn" onClick="javascript:this.parentElement.remove();">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    {/section}
</div>



