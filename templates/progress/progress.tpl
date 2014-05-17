<h2>Progres</h2>
<p>Aici puteți vedea foarte rapid și ușor situația traducerilor. Dați click pe numărul unei probleme pentru a o vedea.</p>

<br />
<br />

<div style="padding:10px;border:1px solid #ccc;float:left;margin-right:20px;">
    <img src="images/levels/level_{level}.png" alt="Nivel {level}" />
</div>
<h2 style="font-size:300%;">Traducător</h2>
<h3 style="font-size:200%;">Nivel {level}</h3>
<div class="clear"></div>

<br />

<h3>Tradus {nr_problems_translated} din {nr_problems_total}</h3>
<div style="width:600px;border:1px solid #999;padding:1px;margin-top:2px;" title="Tradus {percentage_problems_translated}% din probleme">
        <div style="width:{percentage_problems_translated}%;height:5px;background:url(images/gradient_bar.png) 0 0 no-repeat;"></div>
</div>

<br />

<h3>Nivele Completate</h3>

<table class="grid">
    <tbody>
        {levels}
    </tbody>
</table>

<br />
<br />

<h3>Probleme Traduse</h3>

<table class="grid">
    {problem_cells}
</table>

<br />
<br />