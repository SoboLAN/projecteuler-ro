<?php

//returns a DOMNode's content, without stripping internal HTML tags
function DOMinnerHTML($element)
{
    $innerHTML = "";
    $children = $element->childNodes;
        
    foreach ($children as $child) {
        $tmp_dom = new DOMDocument();
        $tmp_dom->appendChild($tmp_dom->importNode($child, true));
        $innerHTML .= trim($tmp_dom->saveHTML());
    }
    
    return $innerHTML;
}
    
//returns the problem content from the specified URL.
//it will be in raw HTML form.
//if no "<div class="problem_content"..." element is found, "NONE" is returned
function getProblemContent($url)
{
    //get all the html content
    $html = file_get_contents($url);
        
    //no content retrieved...
    if ($html === false OR strlen($html) < 40) {
        return 'NONE';
    }
        
    //parsing errors occur often, so this will prevent them
    //from showing up to the user, they will be handled internally
    libxml_use_internal_errors(true);
    
    //the Document Object Model
    $dom = new DOMDocument();
    
    //load the HTML page in the DOM
    $dom->loadHTML($html);
    
    //the problem content is found in a div, so let's get all the div's
    $list = $dom->getElementsByTagName('div');
    
    for ($i = 0; $i < $list->length; $i++) {
        //traversing all the div's
        $item = $list->item($i);
            
        //attributes needed, since we're looking for "<div class="problem_content">"
        $attr = $item->attributes;
            
        if (! is_null($attr)) {
            $s = $attr->getNamedItem('class');
                
            if (! is_null($s) && $s->nodeValue == 'problem_content') {
                //if the correct item is found, it needs to go through
                //the DOMinnerHTML function. otherwise, the internal HTML code
                //will be stripped when the content is extracted
                return DOMinnerHTML($item);
            }
        }
    }
        
    //if the problem is not found... well... sorry
    return 'NONE';
}
    
if (! isset($_GET['problem']) || ! isset($_GET['lang'])) {
    exit(-1);
}
    
$problemID = $_GET['problem'];
$lang = $_GET['lang'];
    
$available_languages = array('ro', 'en', 'ru', 'es', 'kr');

if (in_array($lang, $available_languages) === FALSE) {
    exit(-3);
}
    
//eliminate some junk... (people can put all sorts of stuff in this thing)...
if (strlen($problemID) > 4 || strpos($problemID, '.') !== false || strpos($problemID, '/') !== false) {
    exit(-4);
}

require_once 'include/peproblem.class.php';

//create the problem object
$problemObj = PEProblem::withID($problemID);

//if no such problem exists, return 'NONE'
if ($problemObj === false) {
    $array = array ('translation' => 'NONE');
    
    echo 'processTranslation (' . json_encode($array) . ');';
    
    exit(0);
}

$theData = 'NONE';
$query = 'NONE';

switch ($lang) {
    case 'ro':    //romanian
    {
        $theData = $problemObj->isTranslated() ? $problemObj->getTextRO() : 'NONE';
        
        $query = 'UPDATE gmonkeyaccesses SET accesses_ro = accesses_ro + 1 WHERE problem_id = ' . $problemID;
            
        break;
    }
    case 'en':    //english
    {
        $theData = $problemObj->getTextEN();
        
        $query = 'UPDATE gmonkeyaccesses SET accesses_en = accesses_en + 1 WHERE problem_id = ' . $problemID;
        
        break;
    }
    case 'ru':    //russian
    {
        $theData = getProblemContent('http://euler.jakumo.org/problems/view/' . $_GET['problem'] . '.html');
        
        $query = 'UPDATE gmonkeyaccesses SET accesses_ru = accesses_ru + 1 WHERE problem_id = ' . $problemID;
        
        break;
    }
    case 'es':    //spanish
    {
        $theData = getProblemContent('http://euleres.tk/problems.php?id=' . $_GET['problem']);
        
        $query = 'UPDATE gmonkeyaccesses SET accesses_es = accesses_es + 1 WHERE problem_id = ' . $problemID;
        
        break;
    }
    case 'kr':    //korean
    {
        $theData = getProblemContent('http://euler.synap.co.kr/prob_detail.php?id=' . $_GET['problem']);
        
        $query = 'UPDATE gmonkeyaccesses SET accesses_kr = accesses_kr + 1 WHERE problem_id = ' . $problemID;
        
        break;
    }
}

if ($query !== 'NONE' && $theData !== 'NONE') {
    require_once ('include/db.class.php');
    
    $db = new DBConn();
    
    $r = $db->executeQuery($query);
    
    $db->closeConnection();
}

$array = array('translation' => $theData);

echo 'processTranslation (' . json_encode($array) . ');';
