<?php

require_once 'autoload.php';

use ProjectEuler\Main\Site;
use ProjectEuler\Content\StatisticsContent;
use ProjectEuler\Renderers\StatisticsRenderer;

$site = new Site();
$statisticsContent = new StatisticsContent();
$statistics = $statisticsContent->getStatistics();

$statisticsTpl = file_get_contents('templates/statistics.tpl');

$renderer = new StatisticsRenderer();

$renderedStatistics = $renderer->renderStatistics($statisticsTpl, $statistics);

echo $renderedStatistics;