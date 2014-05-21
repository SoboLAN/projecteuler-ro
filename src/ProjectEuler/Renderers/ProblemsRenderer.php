<?php

namespace ProjectEuler\Renderers;

use ProjectEuler\Renderers\GeneralRenderer;
use ProjectEuler\Main\Config;

class ProblemsRenderer extends GeneralRenderer
{
    public function renderProblemsPage($template, $content)
    {
        return str_replace(
            array(
                '{tags}',
                '{problems}',
                '{pagination}'
            ),
            array(
                $content['rendered_tags'],
                $content['rendered_problems'],
                $content['rendered_pagination']
            ),
            $template
        );
    }
    
    public function renderProblems($translatedTpl, $notTranslatedTpl, $problems)
    {
        $result = '';
        foreach ($problems as $problem) {
            if ($problem['is_translated']) {
                
                $publishDateString = date('d F Y', $problem['publish_date']);
                $publishDateTranslated = $this->translateDate($publishDateString);
                
                $result .= str_replace(
                    array(
                        '{problem_id}',
                        '{publish_date}',
                        '{problem_title}',
                        '{hits}'
                    ),
                    array(
                        $problem['id'],
                        'Publicată în ' . $publishDateTranslated,
                        $problem['title_romanian'],
                        $problem['hits']
                    ),
                    $translatedTpl
                );
            } else {
                $result .= str_replace(
                    array(
                        '{problem_id}',
                        '{problem_title}',
                        '{hits}'
                    ),
                    array(
                        $problem['id'],
                        $problem['title_english'],
                        $problem['hits']
                    ),
                    $notTranslatedTpl
                );
            }
        }
        
        return $result;
    }
    
    public function renderTagsTable($tagTpl, $tags)
    {
        $tagCount = count($tags);
        $maxTagsPerColumn = Config::getValue('problems.max_tags_per_column');
        
        $rowCount = $this->calculateTagsTableRows($tagCount, $maxTagsPerColumn);
        $rowLimit = $this->calculateTagsTableRowLimit($tagCount, $rowCount, $maxTagsPerColumn);
        
        $result = '';
        for ($i = 0; $i < $rowLimit; $i++) {
            $result .= '<tr>';
            
            for ($j = 0; $j < $maxTagsPerColumn; $j++) {
                
                $index = $i * $maxTagsPerColumn + $j;
                $checkedValue = $tags[$index]['checked'] ? 'checked' : '';
                
                $result .= str_replace(
                    array(
                        '{tag_id}',
                        '{tag_name}',
                        '{checked}',
                        '{occurrences}'
                    ),
                    array(
                        $tags[$index]['id'],
                        $tags[$index]['name'],
                        $checkedValue,
                        $tags[$index]['occurrences']
                    ),
                    $tagTpl
                );
            }
            
            $result .= '</tr>';
        }

        if ($tagCount % $maxTagsPerColumn != 0) {
            $remainingTags = $tagCount - (($rowCount - 1) * $maxTagsPerColumn);
            $start = $rowLimit * $maxTagsPerColumn;
            
            $result .= '<tr>';
            
            for ($i = $start; $i < $start + $remainingTags; $i++) {
                
                $checkedValue = $tags[$i]['checked'] ? 'checked' : '';
                
                $result .= str_replace(
                    array(
                        '{tag_id}',
                        '{tag_name}',
                        '{checked}',
                        '{occurrences}'
                    ),
                    array(
                        $tags[$i]['id'],
                        $tags[$i]['name'],
                        $checkedValue,
                        $tags[$i]['occurrences']
                    ),
                    $tagTpl
                );
            }
            
            for ($i = $remainingTags + 1; $i <= $maxTagsPerColumn; $i++) {
                $result .= '<td>&nbsp;&nbsp;</td>';
            }
            
            $result .= '</tr>';
        }
        
        return $result;
    }
    
    private function calculateTagsTableRows($tagCount, $maxTagsPerColumn)
    {
        $rowCount = ($tagCount % $maxTagsPerColumn == 0) ?
                    ($tagCount / $maxTagsPerColumn) :
                    floor($tagCount / $maxTagsPerColumn) + 1;
        
        return $rowCount;
    }
    
    private function calculateTagsTableRowLimit($tagCount, $rowCount, $maxTagsPerColumn)
    {
        $rowLimit = ($tagCount % $maxTagsPerColumn == 0) ?
                    $rowCount :
                    $rowCount - 1;
        
        return $rowLimit;
    }
}
