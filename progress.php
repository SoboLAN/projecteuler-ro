<?php

require_once 'autoload.php';

use ProjectEuler\Main\Site;
use ProjectEuler\Content\ProgressContent;
use ProjectEuler\Renderers\ProgressRenderer;
use ProjectEuler\Main\PEException;

try {
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