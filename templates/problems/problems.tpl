<h2>Probleme</h2>
<p>Aici găsiți traducerile problemelor. Problemele care sunt marcate ca fiind netraduse vor primi traducerea în cel mai scurt timp posibil. Mai multe probleme vor fi adaugate pe măsură ce apar pe <a href="http://projecteuler.net/">ProjectEuler.net</a>.</p>
<p>Dacă aveți browser-ul Mozilla Firefox și extensia GreaseMonkey instalată, atunci vă recomand să instalați scriptul de traducere (link-ul e sus dreapta). În felul acesta, veți avea traducerile disponibile pe site-ul mamă, fără să fiți nevoiți să vizitați acest site. Un tutorial și un filmuleț demonstrativ sunt disponibile pe acea pagina, pentru o instalare cât mai ușoară.</p>
<p>Puteți vizualiza pe o singură pagină atât <a href="showall.php?translated=yes">toate problemele traduse</a> cât și <a href="showall.php?translated=no">cele netraduse</a>.</p>

<form action="problems.php" method="get">
    <div id="tags-table">
        <table class="grid">
            <tbody>
                {tags}
            </tbody>
        </table>
    </div>

    <br />

    <input type="hidden" name="page" value="1" />
    <input id="submit-btn" type="submit" value="Filtrează" />
</form>

<br />

{pagination}

<div class="clear"></div>
<br />

<table class="grid" style="width:1000px">
    <tr>
        <th style="width:40px;"><strong><span style="color:#000;text-decoration:underline;">Nr</span></strong></th>
        <th style="width:360px;"><b>Descriere / Titlu</b></th>
        <th style="width:40px;"><b>Vizualizări</b></th>
    </tr>
    {problems}
</table>

<br />

{pagination}