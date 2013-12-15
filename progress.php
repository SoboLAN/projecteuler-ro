<?php
    const PROBLEMS_PER_ROW = 20;
    
    function getRow ($problems, $rowNr)
    {
        $output = '<tr>';
        
        $start = (($rowNr - 1) * PROBLEMS_PER_ROW) + 1;
        $end = $start + PROBLEMS_PER_ROW - 1;
        
        for ($i = $start; $i <= $end && $i <= count ($problems); $i++)
        {
            $theProblem = $problems[$i - 1];
            
            if ($theProblem->isTranslated ())
            {
                $output .= '
                <td class="table_cell_done"><a href="problem.php?id=' .
                $theProblem->getProblemID () .
                '"><div class="info">' .
                $theProblem->getProblemID () .
                '<span><strong>Problema ' .
                $theProblem->getProblemID () .
                ' (publicată în ' . 
                $theProblem->getPublishDate () .
                ')</strong><br />&quot;' .
                $theProblem->getTitleRO () .
                '&quot;</span>' .
                '</div></a></td>';
            }
            else
            {
                $output .= '
                <td class="table_cell_not_done"><div>' .
                $theProblem->getProblemID () .
                '</div></td>';
            }
        }
        
        $output .= '</tr>';
        
        return $output;
    }

    require ('include/db.class.php');
    
    $dbconn = new DBConn ();
    
    $query = "SELECT problem_id, is_translated, publish_date, title_romanian FROM translations ORDER BY problem_id ASC";
    
    $r = $dbconn->executeQuery ($query);
    
    if (! $r)
    {
        echo 'ERROR';
        exit (-1);
    }

    require ('include/peproblem.class.php');

    $problems = array ();
    
    $nrProblemsTranslated = 0;
    
    $row = $dbconn->nextRowAssoc ();
    
    while ($row !== FALSE)
    {
        $id = (int) $row['problem_id'];
        
        $newProblem = new PEProblem ($id, $row['is_translated'], ' ', $row['title_romanian'], ' ', ' ');
        
        $newProblem->setPublishDate ($row['publish_date']);
        
        $problems[] = $newProblem;
        
        if ($row['is_translated'] == '1')
        {
            $nrProblemsTranslated++;
        }
        
        $row = $dbconn->nextRowAssoc ();
    }
    
    $problemCount = $dbconn->getRowCount ();
    
    $dbconn->closeConnection ();
    
    $rowCount = ($problemCount % PROBLEMS_PER_ROW == 0) ? ($problemCount / PROBLEMS_PER_ROW) : (floor ($problemCount / PROBLEMS_PER_ROW) + 1);
    
    $percentageDone = floor ((100 * $nrProblemsTranslated) / $problemCount);
    
    $level = ($nrProblemsTranslated % 25 == 0) ? ($nrProblemsTranslated / 25) : floor ($nrProblemsTranslated / 25);

    require ('header.php');
    
    echo '<div id="content">

    <h2>Progres</h2>
    <p>Aici puteți vedea foarte rapid și ușor situația traducerilor. Dați click pe numărul unei probleme pentru a o vedea.</p>
    <br /><br />

    <div style="padding:10px;border:1px solid #ccc;float:left;margin-right:20px;"><img src="images/levels/level_' . $level . '.png" alt="Nivel ' . $level . '" /></div>
    <h2 style="font-size:300%;">Traducător</h2>
    <h3 style="font-size:200%;">Nivel ' . $level . '</h3>
    <div class="clear"></div>
    <br />
    <h3>Tradus ' . $nrProblemsTranslated . ' din ' . $problemCount . '</h3>
    <div style="width:600px;border:1px solid #999;padding:1px;margin-top:2px;" title="Tradus ' . $percentageDone . '% din probleme">
        <div style="width:' . $percentageDone . '%;height:5px;background:url(images/gradient_bar.png) 0 0 no-repeat;"></div>
    </div>
    <br />';

    $max_level = floor($problemCount / 25) - 1;
    $open = false;

    echo '<h3>Nivele Completate</h3>
    <table class="grid">
        <tbody>';

    for ($i = 1; $i <= $level; $i++)
    {
        if (($i - 1) % 10 == 0)
        {
            echo '<tr>';
            $open = true;
        }
        
        echo '<td style="width=70px;height:75px;vertical-align:middle">
            <div style="text-align:center">
                <a href="#">
                    <div class="info" style="cursor:pointer">
                        <img title="" alt="Nivelul ' . $i . '" style="width:60px" src="images/levels/level_' . $i . '.png">
                        <span style="width:150px;color:#555;top:50px;">
                            <strong>Nivelul ' . $i . '</strong>
                            <br />
                            Tradus ' . (25 * $i) . ' de probleme
                        </span>
                    </div>
                </a>
            </div>
        </td>';
        
        if ($i % 10 == 0)
        {
            echo '</tr>';
            $open = false;
        } 
    }
    
    for ($i = $level + 1; $i <= $max_level; $i++)
    {
        if (($i - 1) % 10 == 0)
        {
            echo '<tr>';
            $open = true;
        }
        
        echo '<td style="width=70px;height:75px;vertical-align:middle">
            <div style="text-align:center">
                <div style="font-family:\'Courier New\',monospace;color:#999">
                    Tradu
                    <br />
                    <span style="font-size:200%">' . (25 * $i) . '</span>
                    <br />
                    probleme
                </div>
            </div>
        </td>';
        
        if ($i % 10 == 0)
        {
            echo '</tr>';
            $open = false;
        }
    }
    
    if ($open)
    {
        echo '</tr>';
    }
    
    echo '</table><br /><br />';
    
    echo '<h3>Probleme Traduse</h3>

    <table class="grid">';

    for ($i = 1; $i <= $rowCount; $i++)
    {
        echo getRow ($problems, $i);
    }
    
    echo '</table>
        <br /><br />
        </div>';
        
    require ('footer.php');
    
    echo '</div>
        </body>
        </html>';
?>