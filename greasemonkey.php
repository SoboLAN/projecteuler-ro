<?php
    require ('header.php');
?>

<div id="content">

<table style="width:100%" class="noprint">
<tr>
    <td><h2>Script GreaseMonkey</h2></td>
    <td><div style="text-align:right;"><img src="images/greasemonkey.logo.png" title="GreaseMonkey Logo" alt="GreaseMonkey Logo" /></div></td>
</tr>
</table>

<h3>Ce e GreaseMonkey ?</h3>
<p>GreaseMonkey este o extensie construită pentru browser-ul Mozilla Firefox. Această extensie permite scrierea de cod JavaScript și injectarea acestuia
în cadrul unui anumit site pentru a obține o anumită funcționalitate.</p>

<h3>Trebuie să știu să scriu așa ceva pentru a putea folosi script-ul de aici ?</h3>
<p>Nu, nu trebuie să știi așa ceva. Tot ce trebuie să faci e să urmezi câțiva pași simpli din tutorialul de mai jos pentru instalarea script-ului, atâta tot.</p>

<h3>Deci ce face acest script până la urmă ?</h3>
<p>Script-ul acesta permite vizionarea traducerilor direct pe ProjectEuler.net, fără a fi nevoie vizitarea acestui site. Dacă script-ul a fost instalat corect,
atunci o să vă apară 2 stegulețe în partea de sus a paginii atunci când vizionați textul unei probleme. Dând click pe oricare din aceste steaguri, veți putea
comuta între varianta în limba engleză a problemei și varianta în limba română. Pentru a vedea această funcționalitate în acțiune, vedeți filmulețul de mai jos:</p>
<br />

<p>
    <object id="Object1" type="application/x-shockwave-flash" data="player_flv_mini.swf" width="854" height="480">
        <param name="movie" value="player_flv_mini.swf" />
        <param name="wmode" value="opaque" />
        <param name="allowScriptAccess" value="sameDomain" />
        <param name="quality" value="high" />
        <param name="menu" value="true" />
        <param name="autoplay" value="false" />
        <param name="autoload" value="false" />
        <param name="FlashVars" value="flv=translation.demo.flv&width=854&height=480&autoplay=0&autoload=0&buffer=5&playercolor=000000 &loadingcolor=9b9a9a&buttoncolor=ffffff&slidercolor=ffffff" />
    </object>
</p>

<h3>Cum îl instalez ?</h3>
<p>Pentru a putea folosi această funcționalitate trebuie să aveți browser-ul Mozilla Firefox instalat împreună cu extensia GreaseMonkey (Firefox minim versiunea 9.0). Chiar dacă instalarea
acestor 2 programe este foarte simplă, felul în care se face asta e dincolo de scopul acestui tutorial. Dacă aveți dificultăți instalând oricare din cele 2 programe,
căutați ajutor pe Internet (sau sunați un prieten :D) .</p>

<p>
<ul>
    <li>Primul lucru care trebuie să-l faceți e să descărcați script-ul propriu zis de aici: <a href="projecteuler.translate.user.js"><b>Descarcă script</b></a>. (click dreapta și alegeți &quot;Save as / Save Link as&quot;)
        <br />
        Acest script a fost actualizat ultima oară în <strong>13 Ianuarie 2012</strong>.</li>
    <li>Faceți drag &amp; drop al fișierului în browser-ul Mozilla Firefox. O să apară o fereastră similară cu cea de mai jos:
        <br /><br />
        <img src="images/greasemonkey.install.png" title="Instalare Script" />
        <br /><br />
        Apăsați pe butonul "Install". Acum aveți script-ul instalat.
        </li>
    <li>Intrați pe <a href="http://projecteuler.net/">ProjectEuler.net</a> și testați funcționalitatea pentru a vă asigura că totul funcționează cum trebuie.</li>
</ul>
</p>

<br />

<h3>Pot folosi altceva în afară de GreaseMonkey pentru a avea această funcționalitate ?</h3>
<p>GreaseMonkey este cel mai bun și mai stabil program de genul acesta. Alternative pentru alte browsere au apărut de-a lungul timpului, cum ar fi
TamperMonkey (pentru Google Chrome) și multe altele. Acest script nu este în nici un fel legat de GreaseMonkey
(cele mai multe sunt pentru că folosesc funcții din familia GM_* care fac parte din API-ul GreaseMonkey-ului) și poate fi folosit
pe orice browser (+ extensie) care suporta așa ceva. Daca descoperiți că funcționează în Opera, de exemplu, atunci folosiți-l așa.</p>

<h3>Când dau click pe un steguleț nu se întâmplă nimic. De ce ?</h3>
<p>Chiar dacă toate eforturile posibile se fac pentru a traduce aceste probleme, asta va dura destul de mult. De obicei, când nu se întâmplă nimic, înseamnă
că traducerea pentru acea problemă nu este disponibilă încă. Recomand să intrați pe acest site în secțiunea <a href="progress.php">Progres</a> sau
în secțiunea <a href="problems.php?page=1">Probleme</a> și să verificați dacă într-adevăr acea traducere există. Dacă există și tot nu funcționează
script-ul pentru o anumita problemă, atunci vă rog folosiți formularul de Contact și raportați această problemă, preferabil cu cât mai multe detalii posibil.</p>

<h3>Trebuie să actualizez script-ul mereu pentru a avea acces la toate traducerile ?</h3>
<p>Nu, nu trebuie actualizat mereu. Script-ul este construit în așa fel încât, în momentul în care o traducere devine disponibilă, veți putea să o vedeți
pe site-ul mamă. În schimb, unele modificări și reparații periodice vor fi probabil necesare, caz în care recomand actualizarea script-ului la cea mai noua versiune.
Atât în interiorul script-ului cât și lângă link-ul de descărcare de mai sus se specifică ultima dată la care s-a facut actualizarea. În felul acesta puteți
decide dacă trebuie actualizat sau nu.</p>

<h3>Se poate folosi acest script pentru traduceri în alte limbi ?</h3>
<p>Desigur. Script-ul a fost construit cu scalabilitate în minte: poate fi extins pentru a include traduceri în zeci de limbi fără nici un efort tehnic.
Numărul mare de probleme însă (care tot crește) face ca efortul de traducere să fie considerabil, motiv pentru care
există foarte puține limbi în care sunt disponibile aceste probleme.</p>


<div class="clear"></div>
<br />

<div class="clear"></div>

</div>

<?php require ('footer.php'); ?>

</div>

</body>
</html>