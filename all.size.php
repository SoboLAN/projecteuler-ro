<?php
    require_once ('include/db.class.php');
    
    $dbconn = new DBConn ();
    
    $r = $dbconn->executeQuery ("SELECT title_english, title_romanian, text_english, text_romanian, is_translated FROM translations");
    
    if (! $r)
    {
        die ('db error 1');
    }
    
    $rowCount = $dbconn->getRowCount ();
    
    $romanianCount = 0;
    
    $row = $dbconn->nextRowAssoc ();
    
    $totalSizeTitleEN = 0;
    $totalSizeTitleRO = 0;
    $totalSizeTextEN = 0;
    $totalSizeTextRO = 0;
    
    $minTitleEN = 1000 * 1000;
    $maxTitleEN = 0;
    $minTitleRO = 1000 * 1000;
    $maxTitleRO = 0;
    $minTextEN = 1000 * 1000;
    $maxTextEN = 0;
    $minTextRO = 1000 * 1000;
    $maxTextRO = 0;
    
    while ($row !== FALSE)
    {
        $totalSizeTitleEN += strlen ($row['title_english']);
        $totalSizeTitleRO += strlen ($row['title_romanian']);
        $totalSizeTextEN += strlen ($row['text_english']);
        $totalSizeTextRO += strlen ($row['text_romanian']);
        
        $isTranslated = $row['is_translated'] == '1' ? true : false;
        
        if (strlen ($row['title_english']) < $minTitleEN)
        {
            $minTitleEN = strlen ($row['title_english']);
        }
        
        if (strlen ($row['title_english']) > $maxTitleEN)
        {
            $maxTitleEN = strlen ($row['title_english']);
        }
        
        if ($isTranslated)
        {
            if (strlen ($row['title_romanian']) < $minTitleRO)
            {
                $minTitleRO = strlen ($row['title_romanian']);
            }
            
            if (strlen ($row['title_romanian']) > $maxTitleRO)
            {
                $maxTitleRO = strlen ($row['title_romanian']);
            }
        
            if (strlen ($row['text_romanian']) < $minTextRO)
            {
                $minTextRO = strlen ($row['text_romanian']);
            }
        
            if (strlen ($row['text_romanian']) > $maxTextRO)
            {
                $maxTextRO = strlen ($row['text_romanian']);
            }
            
            $romanianCount++;
        }

        if (strlen ($row['text_english']) < $minTextEN)
        {
            $minTextEN = strlen ($row['text_english']);
        }
        
        if (strlen ($row['text_english']) > $maxTextEN)
        {
            $maxTextEN = strlen ($row['text_english']);
        }

        $row = $dbconn->nextRowAssoc ();
    }
    
    $dbconn->closeConnection ();
    
    echo '<!DOCTYPE html>
        <html>
        <head>
        <style type="text/css">
            #stat_table
            {
                width: 600px;
                border: 1px solid;
                background-color: #F4D9D9;
            }
            #stat_table td
            {
                border: 1px solid #9aa;
                text-align: center;
            }
        </style>
        </head>
        <body>
        <table id="stat_table">
        <tr><td>&nbsp;</td><td><b>English</b></td><td><b>Romanian</b></td></tr>
        <tr><td><b>Total Body Size</b></td><td>' . number_format (1.0 * $totalSizeTextEN) . ' characters</td><td>' . number_format (1.0 * $totalSizeTextRO) . ' characters</td></tr>
        <tr><td><b>Minimum Body Size</b></td><td>' . number_format (1.0 * $minTextEN) . ' characters</td><td>' . number_format (1.0 * $minTextRO) . ' characters</td></tr>
        <tr><td><b>Maximum Body Size</b></td><td>' . number_format (1.0 * $maxTextEN) . ' characters</td><td>' . number_format (1.0 * $maxTextRO) . ' characters</td></tr>
        <tr><td><b>Average Body Size</b></td><td>' . number_format (1.0 * ($totalSizeTextEN / $rowCount), 2) . ' characters</td><td>' . number_format (1.0 * ($totalSizeTextRO / $romanianCount), 2) . ' characters</td></tr>
        <tr><td><b>Total Title Size</b></td><td>' . number_format (1.0 * $totalSizeTitleEN) . ' characters</td><td>' . number_format (1.0 * $totalSizeTitleRO) . ' characters</td></tr>
        <tr><td><b>Minimum Title Size</b></td><td>' . number_format (1.0 * $minTitleEN) . ' characters</td><td>' . number_format (1.0 * $minTitleRO) . ' characters</td></tr>
        <tr><td><b>Maximum Title Size</b></td><td>' . number_format (1.0 * $maxTitleEN) . ' characters</td><td>' . number_format (1.0 * $maxTitleRO) . ' characters</td></tr>
        <tr><td><b>Average Title Size</b></td><td>' . number_format (1.0 * ($totalSizeTitleEN / $rowCount), 2) . ' characters</td><td>' . number_format (1.0 * ($totalSizeTitleRO / $romanianCount), 2) . ' characters</td></tr>
        </table>
        <br />
        <i>The above data is computed for ' . $rowCount . ' English problems and ' . $romanianCount . ' Romanian problems.</i>
        </body></html>';
?>
