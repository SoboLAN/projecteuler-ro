<?php

require_once 'autoload.php';

use ProjectEuler\Main\Site;
use ProjectEuler\Content\ProgressContent;
use ProjectEuler\Renderers\ProgressRenderer;

$site = new Site();
$progressContent = new ProgressContent();
$problems = $progressContent->getProblemsInfo();

$nrProblemsTranslated = 0;
foreach ($problems as $problem) {
    if ($problem['is_translated']) {
        $nrProblemsTranslated++;
    }
}

$nrProblemsTotal = count($problems);

$progressTpl = file_get_contents('templates/progress/progress.tpl');
$translatedCellTpl = file_get_contents('templates/progress/translated_cell.tpl');
$notTranslatedCellTpl = file_get_contents('templates/progress/not_translated_cell.tpl');
$levelReachedTpl = file_get_contents('templates/progress/level_reached.tpl');
$levelNotReachedTpl = file_get_contents('templates/progress/level_not_reached.tpl');

$renderer = new ProgressRenderer();

$renderedLevels = $renderer->renderLevels($levelReachedTpl, $levelNotReachedTpl, $nrProblemsTotal, $nrProblemsTranslated);
$renderedCells = $renderer->renderCells($translatedCellTpl, $notTranslatedCellTpl, $problems, $nrProblemsTotal);

$finalContent = $renderer->renderProgressPage(
    $progressTpl,
    array('rendered_levels' => $renderedLevels, 'rendered_cells' => $renderedCells),
    $nrProblemsTotal,
    $nrProblemsTranslated
);

$htmlout = $site->getFullPageTemplate('progress.php');

$htmlout = str_replace('{page_content}', $finalContent, $htmlout);

echo $htmlout;