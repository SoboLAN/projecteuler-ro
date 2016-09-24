<?php

namespace ProjectEuler\Content;

class TranslationsContent
{
    const LANG_RO = 'ro';
    const LANG_EN = 'en';
    const LANG_RU = 'ru';
    const LANG_DE = 'de';
    const LANG_KR = 'kr';
    
    private $lang;
    private $problemId;
    private $problemContent;
    
    public static function isLangValid($lang)
    {
        return in_array($lang, array(self::LANG_DE, self::LANG_EN, self::LANG_KR, self::LANG_RO, self::LANG_RU));
    }
    
    public function __construct($lang, $problemId)
    {
        $this->lang = $lang;
        $this->problemId = $problemId;
        $this->problemContent = new ProblemContent();
    }
    
    public function getText()
    {
        switch ($this->lang) {
            case self::LANG_RO:
                return $this->getRomanianText();
            case self::LANG_EN:
                return $this->getEnglishText();
            case self::LANG_RU:
                return $this->getRussianText();
            case self::LANG_DE:
                return $this->getGermanText();
            case self::LANG_KR:
                return $this->getKoreanText();
            default:
                return null;
        }
    }
    
    private function getRomanianText()
    {
        $problemData = $this->problemContent->getProblem($this->problemId);

        if (empty($problemData) || ! $problemData['is_translated']) {
            return null;
        }

        return $problemData['text_romanian'];
    }
    
    private function getEnglishText()
    {
        $problemData = $this->problemContent->getProblem($this->problemId);

        if (empty($problemData) || ! $problemData['is_translated']) {
            return null;
        }

        return $problemData['text_english'];
    }
    
    private function getRussianText()
    {
        $url = sprintf('http://euler.jakumo.org/problems/view/%d.html', $this->problemId);
        
        $text = $this->getProblemContent($url);
        
        return $text;
    }
    
    private function getGermanText()
    {
        $url = sprintf('http://projekteuler.de/problems/%d/json', $this->problemId);
        $rawData = file_get_contents($url);

        $problemInfo = json_decode($rawData, true);
        if (! is_null($problemInfo) && $problemInfo['translated'] == true) {
            return $problemInfo['text'];
        } else {
            return null;
        }
    }
    
    private function getKoreanText()
    {
        $url = sprintf('http://euler.synap.co.kr/prob_detail.php?id=%d', $this->problemId);
        
        $text = $this->getProblemContent($url);
        
        return $text;
    }
    
    /**
     * returns the problem content from the specified URL.
     * it will be in raw HTML form.
     * if no "<div class="problem_content"..." element is found, NULL is returned
     */
    private function getProblemContent($url)
    {
        //get all the html content
        $html = file_get_contents($url);

        //no content retrieved...
        if ($html === false || strlen($html) < 20) {
            return null;
        }

        //parsing errors occur often, so this will prevent them
        //from showing up to the user, they will be handled internally
        libxml_use_internal_errors(true);

        //the Document Object Model
        $dom = new \DOMDocument();

        //load the HTML page in the DOM
        $dom->loadHTML($html);

        //the problem content is found in a div, so let's get all the div's
        $list = $dom->getElementsByTagName('div');

        for ($i = 0; $i < $list->length; $i++) {
            //traversing all the div's
            $item = $list->item($i);

            //attributes needed, since we're looking for "<div class="problem_content">"
            $attr = $item->attributes;

            if (! is_null($attr)) {
                $s = $attr->getNamedItem('class');

                if (! is_null($s) && $s->nodeValue == 'problem_content') {

                    //if the correct item is found, it needs to go through
                    //the DOMinnerHTML function. otherwise, the internal HTML code
                    //will be stripped when the content is extracted
                    return $this->DOMinnerHTML($item);
                }
            }
        }

        //if the problem is not found... well... sorry
        return null;
    }
    
    /**
     * returns a DOMNode's content, without stripping internal HTML tags
     */
    private function DOMinnerHTML($element)
    {
        $innerHTML = '';
        $children = $element->childNodes;

        foreach ($children as $child) {
            $tmpDOM = new \DOMDocument();
            $tmpDOM->appendChild($tmpDOM->importNode($child, true));
            $innerHTML .= trim($tmpDOM->saveHTML());
        }

        return $innerHTML;
    }
}