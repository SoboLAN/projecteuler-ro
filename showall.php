<?php

require_once 'autoload.php';

use ProjectEuler\Main\Site;
use ProjectEuler\Content\ShowAllContent;
use ProjectEuler\Renderers\ShowAllRenderer;

if (! isset($_GET['translated'])) {
    header("Location: showall.php?translated=yes");
}

$translatedParam = $_GET['translated'];
if ($translatedParam !== 'yes' && $translatedParam !== 'no') {
    header("Location: problems.php?page=1");
} else {
    $translated = ($translatedParam == 'yes');
}

$site = new Site();
$showAllContent = new ShowAllContent();
$problems = $showAllContent->getProblems($translated);

$tplName = $translated ? 'translated.tpl' : 'not_translated.tpl';
$problemTpl = file_get_contents('templates/showall/' . $tplName);

$renderer = new ShowAllRenderer();

$finalContent = $renderer->renderProblems($problemTpl, $problems);

$htmlout = $site->getFullPageTemplate('problems.php');

$htmlout = str_replace('{page_content}', $finalContent, $htmlout);

echo $htmlout;
