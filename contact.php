<?php

require_once 'autoload.php';

use ProjectEuler\Main\Site;

$site = new Site();

$finalContent = '<p>Funcționalitatea de contact nu este disponibilă momentan.</p>';

$htmlout = $site->getFullPageTemplate('contact.php');

$htmlout = str_replace('{page_content}', $finalContent, $htmlout);

echo $htmlout;
