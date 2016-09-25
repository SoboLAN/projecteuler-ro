<?php

require_once 'autoload.php';

use ProjectEuler\Main\Site;

$site = new Site();

$finalContent = file_get_contents('templates/greasemonkey.tpl');

$htmlout = $site->getFullPageTemplate('greasemonkey.php');

$htmlout = str_replace('{page_content}', $finalContent, $htmlout);

echo $htmlout;
