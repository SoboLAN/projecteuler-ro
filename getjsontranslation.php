<?php

require_once 'autoload.php';

use ProjectEuler\Content\TranslationsContent;
use ProjectEuler\Main\Site;
use ProjectEuler\Main\PEException;

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

if (! isset($_GET['problem']) || ! isset($_GET['lang'])) {
    header('HTTP/1.1 400 Bad Request');
    exit();
}

$problemID = $_GET['problem'];
$lang = $_GET['lang'];

if (!TranslationsContent::isLangValid($lang)) {
    header('HTTP/1.1 400 Bad Request');
    exit();
}

//eliminate some junk... (people can put all sorts of stuff in this thing)...
if (strlen($problemID) > 4 || ! ctype_digit($problemID)) {
    header('HTTP/1.1 400 Bad Request');
    exit();
}

$translationsContent = new TranslationsContent($lang, $problemID);

$result = $translationsContent->getText();

if (is_null($result)) {
    $array = array('translation' => 'NONE');
} else {
    $array = array('translation' => $result);
}

echo sprintf(
    'processTranslation(%s);',
    json_encode($array)
);
