<?php

require_once 'autoload.php';

use ProjectEuler\Main\Site;
use ProjectEuler\PEProblem;
use ProjectEuler\Main\Database;

$site = new Site();

if (! isset ($_GET['id']) or ! $site->isValidID($_GET['id']))
{
    exit (-1);
}

$problem = $_GET['id'];

require_once 'header.php';

$currentProblem = PEProblem::withID ($problem);
    
if ($currentProblem === FALSE)
{
    echo '<div id="content">
            <div style="text-align:center;" class="noprint">
                <p>Această problemă nu există sau nu a fost tradusă încă.</p>
            </div>
        </div>';

    require 'footer.php';

    echo '</div>
        </body>
        </html>';
        
    exit (0);
}
    
if ($currentProblem->isTranslated () === FALSE)
{
    echo '<div id="content">
            <div style="text-align:center;" class="noprint">
                <p>Această problemă nu există sau nu a fost tradusă încă.</p>
            </div>
        </div>';
            
    require 'footer.php';
            
    echo '</div>
        </body>
        </html>';
        
    exit (0);
}
    
$problemTitle = $currentProblem->getTitleRO ();
$problemText = $currentProblem->getTextRO ();
    
$prevProblemID = $problem - 1;
$nextProblemID = $problem + 1;
    
$prevProblem = $prevProblemID > 0 ? PEProblem::withID ($prevProblemID) : FALSE;
$nextProblem = PEProblem::withID ($nextProblemID);

echo '<div id="content">
    <table style="width:100%" class="noprint">
    <tr>';
if ($prevProblem !== FALSE)
{
    echo '
    <td><a href="problem.php?id=' . $prevProblemID . '" style="cursor:pointer;" class="info"><img src="' .
    ($prevProblem->isTranslated () === TRUE ? 'images/icon_back_solved.png' : 'images/icon_back.png') .
    '" alt="Precedenta" /></a></td>';
}
if ($nextProblem !== FALSE)
{
    echo '
    <td><div style="text-align:right;"><a href="problem.php?id=' . $nextProblemID . '" style="cursor:pointer;" class="info"><img src="' .
    ($nextProblem->isTranslated () === TRUE ? 'images/icon_next_solved.png' : 'images/icon_next.png') .
    '" alt="Următoarea" /></a></div></td>';
}
echo '</tr>
</table>

<h2>Problema ' . $problem . '</h2>
    <div style="color:#666;font-size:80%;">' . $currentProblem->getPublishDate () . '</div><br />
<div style="text-align:center"><h3>' . $problemTitle . '</h3></div>
<br />

<div class="problem_content" role="problem">' . $problemText . '</div>
<br />';

$db = Database::getConnection();
    
$statement = $db->prepare('SELECT m.tag_id, t.tag_name FROM tag_mappings m JOIN tags t ON m.tag_id = t.tag_id WHERE m.problem_id = ?');

$statement->bindParam(1, $problem, \PDO::PARAM_INT);
$statement->execute();

if ($statement->rowCount() > 0)
{
    echo '<h3 style="float:left; padding-top:3px;">Tag-uri:</h3><div class="applied-tags">';

    while ($row = $statement->fetch(\PDO::FETCH_OBJ))
    {
        echo '<a href="problems.php?tag' . $row->tag_id . '=1&page=1">' . $row->tag_name . '</a>';
    }
        
    echo '</div>
        <div class="clear"></div>';
}

echo '<div style="text-align:center;" class="noprint">
    <p><a href="http://projecteuler.net/problem=' . $problem . '" target="blank"><h3>&gt;&gt; Vezi problema originală &lt;&lt;</h3></a></p>
    </div>
    </div>';
    
$hitsI = $currentProblem->increaseHits ();
    
if ($hitsI === FALSE)
{
    die ('ERROR');
}
    
require 'footer.php';
echo '</div>
    </body>
    </html>';
