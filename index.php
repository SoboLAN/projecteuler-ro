<?php

require_once 'autoload.php';

use ProjectEuler\Main\Site;
use ProjectEuler\Main\PEException;

try {
    $site = new Site();
    
    $finalContent = file_get_contents('templates/index.tpl');

    $htmlout = $site->getFullPageTemplate('index.php');
    
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

$htmlout = str_replace('{page_content}', $finalContent, $htmlout);

echo $htmlout;
