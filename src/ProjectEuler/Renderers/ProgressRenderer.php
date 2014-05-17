<?php

namespace ProjectEuler\Renderers;

use ProjectEuler\Renderers\GeneralRenderer;
use ProjectEuler\Main\Config;

class ProgressRenderer extends GeneralRenderer
{
    public function renderProgressPage($template, $content, $nrProblemsTotal, $nrProblemsTranslated)
    {
        $levelSize = Config::getValue('progress.level_size');
        $level = $this->calculateLevel($nrProblemsTranslated, $levelSize);
        $percentage = $this->calculateTranslationPercentage($nrProblemsTotal, $nrProblemsTranslated);
        
        return str_replace(
            array(
                '{level}',
                '{nr_problems_total}',
                '{nr_problems_translated}',
                '{percentage_problems_translated}',
                '{levels}',
                '{problem_cells}'
            ),
            array(
                $level,
                $nrProblemsTotal,
                $nrProblemsTranslated,
                $percentage,
                $content['rendered_levels'],
                $content['rendered_cells']
            ),
            $template
        );
    }
    
    public function renderLevels($levelReachedTpl, $levelNotReachedTpl, $nrProblemsTotal, $nrProblemsTranslated)
    {
        $levelSize = Config::getValue('progress.level_size');
        $levelsPerRow = Config::getValue('progress.levels_per_row');
        
        $level = $this->calculateLevel($nrProblemsTranslated, $levelSize);
        $maxLevel = $this->calculateMaximumLevel($nrProblemsTotal, $levelSize);
        
        $open = false;
        
        $result = '';
        
        for ($i = 1; $i <= $level; $i++) {
            if (($i - 1) % $levelsPerRow == 0) {
                $result .= '<tr>';
                $open = true;
            }
            
            $nrProblemsInThisStep = $levelSize * $i;
            
            $result .= str_replace(
                array('{level}', '{nr_problems}'),
                array($i, $nrProblemsInThisStep),
                $levelReachedTpl
            );

            if ($i % $levelsPerRow == 0) {
                $result .= '</tr>';
                $open = false;
            } 
        }
        
        for ($i = $level + 1; $i <= $maxLevel; $i++) {
            if (($i - 1) % $levelsPerRow == 0) {
                $result .= '<tr>';
                $open = true;
            }
            
            $result .= str_replace('{nr_problems}', $levelSize * $i, $levelNotReachedTpl);

            if ($i % $levelsPerRow == 0) {
                $result .= '</tr>';
                $open = false;
            }
        }

        if ($open) {
            $result .= '</tr>';
        }
        
        return $result;
    }
    
    public function renderCells($translatedCellTpl, $notTranslatedCellTpl, $problems, $nrProblemsTotal)
    {
        $problemsPerRow = Config::getValue('progress.problems_per_row');
        $rowCount = $this->calculateNumberOfCellRows($nrProblemsTotal, $problemsPerRow);
        
        $result = '';
        for ($i = 1; $i <= $rowCount; $i++) {
            $result .= '<tr>';
            
            $start = (($i - 1) * $problemsPerRow) + 1;
            $end = $start + $problemsPerRow - 1;
            
            for ($j = $start; $j <= $end && $j <= $nrProblemsTotal; $j++) {
                
                $theProblem = $problems[$j - 1];
                if ($theProblem['is_translated']) {
                    
                    $result .= str_replace(
                        array('{problem_id}', '{publish_date}', '{problem_title}'),
                        array($theProblem['id'], $theProblem['publish_date'], $theProblem['title_romanian']),
                        $translatedCellTpl
                    );
                } else {
                    $result .= str_replace('{problem_id}', $theProblem['id'], $notTranslatedCellTpl);
                }
            }

            $result .= '</tr>';
        }
        
        return $result;
    }
    
    private function calculateLevel($nrProblemsTranslated, $levelSize)
    {
        $level = ($nrProblemsTranslated % $levelSize == 0) ?
                 ($nrProblemsTranslated / $levelSize) :
                 floor($nrProblemsTranslated / $levelSize);
        
        return $level;
    }
    
    private function calculateMaximumLevel($nrProblemsTotal, $levelSize)
    {
        return floor($nrProblemsTotal / $levelSize) - 1;
    }
    
    private function calculateNumberOfCellRows($nrProblemsTotal, $problemsPerRow)
    {
        $rowCount = ($nrProblemsTotal % $problemsPerRow == 0) ?
                    ($nrProblemsTotal / $problemsPerRow) :
                    floor($nrProblemsTotal / $problemsPerRow) + 1;
        
        return $rowCount;
    }
    
    private function calculateTranslationPercentage($nrProblemsTotal, $nrProblemsTranslated)
    {
        return floor(($nrProblemsTranslated * 100) / $nrProblemsTotal);
    }
}
