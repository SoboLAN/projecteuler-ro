<table style="width:100%" class="noprint">
    <tr>
    <td>
   		<a href="problem.php?id={previous_id}" style="cursor:pointer;" class="info"><img src="images/{previous_image}" alt="Precedenta" /></a>
   	</td>
    <td>
        <div style="text-align:right;">
            <a href="problem.php?id={next_id}" style="cursor:pointer;" class="info"><img src="images/{next_image}" alt="Următoarea" /></a>
		</div>
    </td>
    </tr>
</table>

<h2>Problema {problem_id}</h2>
    <div style="color:#666;font-size:80%;">{problem_publish_date}</div><br />
<div style="text-align:center"><h3>{problem_title}</h3></div>

<br />

<div class="problem_content" role="problem">{problem_body}</div>

<br />

<h3 style="float:left; padding-top:3px;">Tag-uri:</h3>
<div class="applied-tags">{tags}</div>
<div class="clear"></div>
<div style="text-align:center;" class="noprint">
    <p><a href="http://projecteuler.net/problem={problem_id}" target="blank"><h3>&gt;&gt; Vezi problema originală &lt;&lt;</h3></a></p>
</div>