<?php

namespace ProjectEuler\Content;

use ProjectEuler\Main\Site;
use ProjectEuler\Content\ProblemsContent;

class ProblemsHelper
{
    private $content;
    private $site;
    
    //to avoid multiple queries, values will accumulate here
    private $container;
    
    public function __construct(ProblemsContent $problemsContent, Site $site)
    {
        $this->content = $problemsContent;
        $this->site = $site;
        $this->container = array();
    }
    
    public function getCleanTags()
    {
        if (isset($this->container['tag_ids'])) {
            $tagIds = $this->container['tag_ids'];
        } else {
            $tagIds = $this->content->getTagIds();
            $this->container['tag_ids'] = $tagIds;
        }
        
        $tags = array ();
        foreach ($tagIds as $tagId) {
            $tag_id = 'tag' . $tagId;
            
            if (isset($_GET[$tag_id]) && $_GET[$tag_id] == '1') {
                $tags[] = (int) $tagId;
            }
        }
        
        return $tags;
    }
    
    //returns TRUE if GET is valid, FALSE otherwise
    public function isValidGET ()
    {
        //check for arrays in _GET
        foreach ($_GET as $element) {
            if (is_array($element)) {
                return false;
            }
        }
        
        if (isset($this->container['nr_pages'])) {
            $nrPages = $this->container['nr_pages'];
        } else {
            $nrPages = $this->content->getNrPages();
            $this->container['nr_pages'] = $nrPages;
        }
        
        //check the "page" argument
        $page = $_GET['page'];
        if (! $this->isValidPage($page, $nrPages)) {
            return false;
        }
        
        //check for very obvious foreign arguments (if it's not "page" or it doesn't start with "tag"... well, too bad)
        foreach ($_GET as $key => $value) {
            if ($key != 'page' && strncmp($key, 'tag', 3) != 0) {
                return false;
            }
        }
        
        $rebuiltGET = $this->rebuildGET($page);
        
        $rawGET = $this->getRawGET();
        
        return (strlen($rawGET) == strlen($rebuiltGET));
    }
    
    public function rebuildGET($page)
    {
        $cleanTags = $this->getCleanTags();
        
        if (isset($this->container['nr_pages'])) {
            $nrPages = $this->container['nr_pages'];
        } else {
            $nrPages = $this->content->getNrPages();
            $this->container['nr_pages'] = $nrPages;
        }
        
        $isValidPage = $this->isValidPage($page, $nrPages);
        
        if (count($cleanTags) == 0) {
            $rebuiltGET = 'page=' . ($isValidPage ? $page : '1');
        } else {
            $rebuiltGET = 'tag' . implode ('=1&tag', $cleanTags) . '=1' . '&page=' . ($isValidPage ? $page : '1');
        }
        
        return $rebuiltGET;
    }
    
    //will return a string containing the GET which the user typed in his address bar.
    // it will have the following structure: "arg1=val1&arg2=val2&arg3=val3"
    public function getRawGET()
    {
        //WARNING: 'REQUEST_URI' is NOT supported (by default) in IIS
        $rawGET = explode('?', $_SERVER['REQUEST_URI']);
        
        return $rawGET[1];
    }
    
    private function isValidPage($page, $nrPages)
    {
        return (strlen($page) <= 2 && ctype_digit($page) && $page <= $nrPages);
    }
}