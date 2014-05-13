<?php

require_once 'autoload.php';

use ProjectEuler\Main\Site;
use ProjectEuler\Content\ProblemContent;
use ProjectEuler\Renderers\ProblemRenderer;
use ProjectEuler\Main\Logger;
use ProjectEuler\Main\PEException;

try {
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
    
    if (count($problem) == 0) {
        $message = 'Non-existent problem ID specified when acccessing problem.php';
        Logger::log($message);
        throw new PEException($message, PEException::INVALID_REQUEST);
    }
    
    $tags = $problemContent->getTags($problemId);
    $neighBorsStatus = $problemContent->areNeighboursTranslated($problemId);
    $problemContent->increaseHits($problemId);
    
    $problemTpl = file_get_contents('templates/problem/problem.tpl');
    $tagTpl = file_get_contents('templates/problem/tag.tpl');
    
    $renderer = new ProblemRenderer();
    
    $renderedProblem = $renderer->renderProblem($problemTpl, $problem, $neighBorsStatus);
    $renderedTags = $renderer->renderTags($tagTpl, $tags);
    
    $finalContent = str_replace('{tags}', $renderedTags, $renderedProblem);
    
    $htmlout = $site->getFullPageTemplate('problems.php');
    
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
