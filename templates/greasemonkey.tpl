<table style="width:100%">
<tr>
    <td><h2>Script GreaseMonkey</h2></td>
    <td>
        <div style="text-align:right;">
            <img src="images/greasemonkey.logo.png" title="GreaseMonkey Logo" alt="GreaseMonkey Logo" />
        </div>
    </td>
</tr>
</table>

<article>
    <h3>Ce e GreaseMonkey ?</h3>
    <p>GreaseMonkey este o extensie construită pentru browser-ul Mozilla Firefox. Această extensie
    permite scrierea de cod JavaScript și injectarea acestuia în cadrul unui anumit site pentru a
    obține o anumită funcționalitate.</p>
</article>

<article>
    <h3>Trebuie să știu să scriu așa ceva pentru a putea folosi script-ul de aici ?</h3>
    <p>Nu, nu trebuie să știi așa ceva. Tot ce trebuie să faci e să urmezi câțiva pași simpli din
    tutorialul de mai jos pentru instalarea script-ului, atâta tot.</p>
</article>

<article>
    <h3>Deci ce face acest script până la urmă ?</h3>
    <p>Script-ul acesta permite vizionarea traducerilor direct pe ProjectEuler.net, fără a fi nevoie
    vizitarea acestui site. Dacă script-ul a fost instalat corect, atunci o să vă apară 5 stegulețe
    în partea de sus a paginii atunci când vizionați textul unei probleme. Dând click pe oricare din
    aceste steaguri, veți putea comuta între variantele în limba engleză, română, rusă, germană și coreeană
    a problemei. Pentru a vedea această funcționalitate în acțiune, vedeți filmulețul de mai jos:</p>
    <br />

    <p>
        <video width="854" height="362" controls autobuffer>
            <source type="video/webm" src="translation.demo.webm">
            <source type="video/mp4" src="translation.demo.mp4">
        </video>
    </p>
</article>

<article>
    <h3>Cum îl instalez ?</h3>
    <p>Pentru a putea folosi această funcționalitate trebuie să aveți browser-ul Mozilla Firefox
    instalat împreună cu extensia GreaseMonkey (Firefox minim versiunea 9.0). Chiar dacă instalarea
    acestor 2 programe este foarte simplă, felul în care se face asta e dincolo de scopul acestui
    tutorial. Dacă aveți dificultăți instalând oricare din cele 2 programe, căutați ajutor pe Internet
    (sau sunați un prieten :D) .</p>

    <p>
        <ul>
            <li>
                Primul lucru care trebuie să-l faceți e să descărcați script-ul propriu zis de
                aici: <a href="https://greasyfork.org/en/scripts/4899-project-euler-problem-translator"><b>Descarcă script</b></a>.
                <br />
                Acest script este momentan la versiunea <strong>1.7</strong> și a fost actualizat ultima
                oară în <strong>24 Septembrie 216</strong>.
            </li>
            <li>
                O să apară o fereastră similară cu cea de mai jos:
                <br />
                <br />
                <img src="images/greasemonkey.install.png" title="Instalare Script" />
                <br />
                <br />
                Apăsați pe butonul "Install". Acum aveți script-ul instalat.
            </li>
            <li>
                Intrați pe <a href="http://projecteuler.net/">ProjectEuler.net</a> și testați
                funcționalitatea pentru a vă asigura că totul funcționează cum trebuie.
            </li>
        </ul>
    </p>
</article>

<article>
    <h3>Pot folosi altceva în afară de GreaseMonkey pentru a avea această funcționalitate ?</h3>
    <p>GreaseMonkey este cel mai bun și mai stabil program de genul acesta. Alternative pentru alte
    browsere au apărut de-a lungul timpului, cum ar fi TamperMonkey (pentru Google Chrome) și multe
    altele. Multe script-uri folosesc funcții din familia GM_*. Aceste funcții fac parte din API-ul
    GreaseMonkey-ului. Prin urmare, ele pot fi folosite doar împreună cu GreaseMonkey. Acest script
    nu folosește nici o funcție din această familie și, prin urmare, poate fi folosit pe orice browser
    (+ extensie) care suportă așa ceva. Dacă descoperiți că funcționează în Opera, de exemplu,
    atunci folosiți-l așa.</p>
</article>

<article>
    <h3>Când intru pe site-ul ProjectEuler nu se întâmplă nimic. De ce ?</h3>
    <p>Dacă folosiţi un browser cu facilitatea Mixed Active Content Blocker activată şi intraţi
    pe varianta HTTPS a site-ului ProjectEuler, atunci script-ul nu o să funcţioneze.
    De exemplu, toate versiunile Mozilla Firefox mai noi de 23 au această funcţie activată în mod implicit.
    Soluţia recomandată este fie să dezactivaţi această funcţionalitate (căutaţi pe Google cum se face),
    fie să folosiţi varianta HTTP a site-ului ProjectEuler.</p>
</article>

<article>
    <h3>Când dau click pe un steguleț nu se întâmplă nimic. De ce ?</h3>
    <p>Chiar dacă toate eforturile posibile se fac pentru a traduce aceste probleme, asta va dura
    destul de mult. De obicei, când nu se întâmplă nimic, înseamnă că traducerea pentru acea problemă
    nu este disponibilă încă. Recomand să intrați pe acest site în secțiunea <a href="progress.php">Progres</a>
    sau în secțiunea <a href="problems.php?page=1">Probleme</a> și să verificați dacă într-adevăr
    acea traducere există. Dacă există și tot nu funcționează script-ul pentru o anumita problemă,
    atunci vă rog folosiți formularul de Contact și raportați această problemă, preferabil cu cât
    mai multe detalii posibil.</p>
</article>

<article>
    <h3>Pot găsi undeva codul sursă al script-ului ?</h3>
    <p>Script-ul are propriul său repository pe GitHub: <a href="https://github.com/SoboLAN/projecteuler-translation-script">https://github.com/SoboLAN/projecteuler-translation-script</a>.</p>
</article>

<article>
    <h3>Trebuie să actualizez script-ul mereu pentru a avea acces la toate traducerile ?</h3>
    <p>Nu, nu trebuie actualizat mereu. Script-ul este construit în așa fel încât, în momentul în
    care o traducere devine disponibilă, veți putea să o vedeți pe site-ul mamă. În schimb, unele
    modificări și reparații periodice vor fi probabil necesare, caz în care recomand actualizarea
    script-ului la cea mai noua versiune. Atât în interiorul script-ului cât și lângă link-ul de
    descărcare de mai sus se specifică ultima dată la care s-a facut actualizarea. În felul acesta
    puteți decide dacă trebuie actualizat sau nu.</p>
</article>

<article>
    <h3>Se poate folosi acest script pentru traduceri în alte limbi ?</h3>
    <p>Desigur. Script-ul a fost construit cu scalabilitate în minte: poate fi extins pentru a
    include traduceri în zeci de limbi fără nici un efort tehnic. Numărul mare de probleme însă (care
    tot crește) face ca efortul de traducere să fie considerabil, motiv pentru care există foarte
    puține limbi în care sunt disponibile aceste probleme.</p>
</article>