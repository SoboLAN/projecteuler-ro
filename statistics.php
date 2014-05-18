<?php

require_once 'autoload.php';

use ProjectEuler\Main\Site;
use ProjectEuler\Content\StatisticsContent;
use ProjectEuler\Renderers\StatisticsRenderer;
use ProjectEuler\Main\PEException;

try {
    $site = new Site();
    $statisticsContent = new StatisticsContent();
    $statistics = $statisticsContent->getStatistics();

    $statisticsTpl = file_get_contents('templates/statistics.tpl');
    
    $renderer = new StatisticsRenderer();
    
    $renderedStatistics = $renderer->renderStatistics($statisticsTpl, $statistics);
    
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

echo $renderedStatistics;