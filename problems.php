<?php

require_once 'autoload.php';

use ProjectEuler\Main\Site;
use ProjectEuler\Main\Config;
use ProjectEuler\Content\ProblemsContent;
use ProjectEuler\Content\ProblemsHelper;
use ProjectEuler\Renderers\ProblemsRenderer;
use ProjectEuler\Renderers\Paginator;
use ProjectEuler\Renderers\PaginationRenderer;

$site = new Site();
$problemsContent = new ProblemsContent();
$helper = new ProblemsHelper($problemsContent, $site);
$renderer = new ProblemsRenderer();
$paginationRenderer = new PaginationRenderer();

$page = $_GET['page'];

if (! $helper->isValidGET()) {
    $rebuiltGET = $helper->rebuildGET($page);

    header("Location: problems.php?$rebuiltGET");
    exit();
}

$tagsFromDB = $problemsContent->getTags();

$tagCount = count($tagsFromDB);
$maxProblemsPerPage = Config::getValue('problems.max_per_page');
$paginationWidth = Config::getValue('problems.pagination_width');

for ($i = 0; $i < $tagCount; $i++) {
    $checked = isset($_GET['tag' . $tagsFromDB[$i]['id']]);
    $tagsFromDB[$i]['checked'] = $checked;
}

$cleanTags = $helper->getCleanTags();

if (count($cleanTags) == 0) {

    $problems = $problemsContent->getProblems($page);

    $problemsCount = $problemsContent->getProblemsCount();
} else {
    $problems = $problemsContent->getFilteredProblems($cleanTags, $page);

    $problemsCount = $problemsContent->getFilteredProblemsCount($cleanTags);
}

if (count($problems) == 0) {
    $rebuiltGET = $helper->rebuildGET(1);
    header("Location: problems.php?$rebuiltGET");
    exit();
}

$paginator = new Paginator($problemsCount, $maxProblemsPerPage, $page, $paginationWidth);
$pages = $paginator->getPagination();

$problemsTpl = file_get_contents('templates/problems/problems.tpl');
$tagTpl = file_get_contents('templates/problems/tags_element.tpl');
$translatedTpl = file_get_contents('templates/problems/problem_translated.tpl');
$notTranslatedTpl = file_get_contents('templates/problems/problem_not_translated.tpl');
$paginationTpl = file_get_contents('templates/problems/pagination.tpl');
$currentLinkTpl = file_get_contents('templates/problems/pagination_element_current.tpl');
$pageLinkTpl = file_get_contents('templates/problems/pagination_element_foreign.tpl');

$renderedTags = $renderer->renderTagsTable($tagTpl, $tagsFromDB);
$renderedProblems = $renderer->renderProblems($translatedTpl, $notTranslatedTpl, $problems);
$renderedPagination = $paginationRenderer->render($paginationTpl, $currentLinkTpl, $pageLinkTpl, $pages, $cleanTags);

$content = array(
    'rendered_tags' => $renderedTags,
    'rendered_problems' => $renderedProblems,
    'rendered_pagination' => $renderedPagination
);

$finalContent = $renderer->renderProblemsPage($problemsTpl, $content);

$htmlout = $site->getFullPageTemplate('problems.php');

$htmlout = str_replace('{page_content}', $finalContent, $htmlout);

echo $htmlout;