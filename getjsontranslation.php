<?php

require_once 'autoload.php';

use ProjectEuler\Content\ProblemContent;
use ProjectEuler\Main\Database;
use ProjectEuler\Main\Logger;
use ProjectEuler\Main\Site;
use ProjectEuler\Main\PEException;

use PDOException as PDOException;

try {
    $site = new Site();
} catch (PEException $ex) {
   switch ($ex->getType()) {
        case PEException::ERROR:
            header('Location: 500.shtml');
            exit();
            break;
        case PEException::INVALID_REQUEST:
            header('Location: 400.shtml');
            exit();
            break;
        case PEException::SITE_OFFLINE:
            header('Location: maintenance.shtml');
            exit();
            break;
        default:
            header('Location: 500.shtml');
            exit();
    }
}


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

if (! in_array($lang, $available_languages)) {
    exit(-3);
}
    
//eliminate some junk... (people can put all sorts of stuff in this thing)...
if (strlen($problemID) > 4 || strpos($problemID, '.') !== false || strpos($problemID, '/') !== false) {
    exit(-4);
}

$theData = 'NONE';
$query = 'NONE';

$problemContent = new ProblemContent();

switch ($lang) {
    case 'ro':    //romanian
    {
        $problemData = $problemContent->getProblem($problemID);
        
        if (empty($problemData)) {
            $array = array ('translation' => 'NONE');
    
            echo 'processTranslation (' . json_encode($array) . ');';

            exit(0);
        }
        
        $theData = $problemData['is_translated'] ? $problemData['text_romanian'] : 'NONE';
        
        $query = 'UPDATE gmonkeyaccesses SET accesses_ro = accesses_ro + 1 WHERE problem_id = ?';
        
        break;
    }
    case 'en':    //english
    {
        $problemData = $problemContent->getProblem($problemID);
        
        if (empty($problemData)) {
            $array = array ('translation' => 'NONE');
    
            echo 'processTranslation (' . json_encode($array) . ');';

            exit(0);
        }
        
        $theData = $problemData['text_english'];
        
        $query = 'UPDATE gmonkeyaccesses SET accesses_en = accesses_en + 1 WHERE problem_id = ?';
        
        break;
    }
    case 'ru':    //russian
    {
        $theData = getProblemContent('http://euler.jakumo.org/problems/view/' . $problemID . '.html');
        
        $query = 'UPDATE gmonkeyaccesses SET accesses_ru = accesses_ru + 1 WHERE problem_id = ?';
        
        break;
    }
    case 'es':    //spanish
    {
        $theData = getProblemContent('http://euleres.tk/problems.php?id=' . $problemID);
        
        $query = 'UPDATE gmonkeyaccesses SET accesses_es = accesses_es + 1 WHERE problem_id = ?';
        
        break;
    }
    case 'kr':    //korean
    {
        $theData = getProblemContent('http://euler.synap.co.kr/prob_detail.php?id=' . $problemID);
        
        $query = 'UPDATE gmonkeyaccesses SET accesses_kr = accesses_kr + 1 WHERE problem_id = ?';
        
        break;
    }
}

if ($query !== 'NONE' && $theData !== 'NONE') {

    try {
        $db = Database::getConnection();
        
        $statement = $db->prepare($query);
        $statement->bindParam(1, $problemID, PDO::PARAM_INT);
        $statement->execute();
        
    } catch (PDOException $ex) {
        $message = "executing $query with problem id $problemID failed";
        Logger::log("$message: " . $ex->getMessage());
        exit(0);
    }
}

$array = array('translation' => $theData);

echo 'processTranslation (' . json_encode($array) . ');';
