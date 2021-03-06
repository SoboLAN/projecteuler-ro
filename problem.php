<?php

require_once 'autoload.php';

use ProjectEuler\Main\Site;
use ProjectEuler\Content\ProblemContent;
use ProjectEuler\Renderers\ProblemRenderer;
use ProjectEuler\Main\Logger;
use ProjectEuler\Main\PEException;

$site = new Site();

if (! isset($_GET['id'])) {
    $message = 'No problem ID specified when accessing problem.php';
    Logger::log($message);
    throw new PEException($message, PEException::INVALID_REQUEST);
} elseif (! $site->isValidID($_GET['id'])) {
    $message = 'Invalid problem ID specified when acccessing problem.php';
    Logger::log($message);
    throw new PEException($message, PEException::INVALID_REQUEST);
}

$problemId = $_GET['id'];

$problemContent = new ProblemContent();

$problem = $problemContent->getProblem($problemId);

if (count($problem) == 0 || ! $problem['is_translated']) {
    if (count($problem) == 0) {
        $message = 'Non-existent problem ID specified when acccessing problem.php';
    } elseif (! $problem['is_translated']) {
        $message = 'Not-translated problem requested when acccessing problem.php';
    }

    Logger::log($message);
    throw new PEException($message, PEException::INVALID_REQUEST);
}

$tags = $problemContent->getTags($problemId);
$neighBorsStatus = $problemContent->areNeighboursTranslated($problemId);
$problemContent->increaseHits($problemId);

$problemTpl = file_get_contents('templates/problem/problem.tpl');
$tagTpl = file_get_contents('templates/problem/tag.tpl');
$tagBoxTpl = file_get_contents('templates/problem/tag_box.tpl');
$prevLinkTpl = file_get_contents('templates/problem/previous_link.tpl');
$nextLinkTpl = file_get_contents('templates/problem/next_link.tpl');

$renderer = new ProblemRenderer();

$renderedProblem = $renderer->renderProblem($problemTpl, $prevLinkTpl, $nextLinkTpl, $problem, $neighBorsStatus);
$renderedTags = $renderer->renderTags($tagBoxTpl, $tagTpl, $tags);

$finalContent = str_replace('{tag_box}', $renderedTags, $renderedProblem);

$htmlout = $site->getFullPageTemplate('problems.php');

$htmlout = str_replace('{page_content}', $finalContent, $htmlout);

echo $htmlout;
