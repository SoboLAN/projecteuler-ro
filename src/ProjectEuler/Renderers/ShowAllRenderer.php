<?php

namespace ProjectEuler\Renderers;

use ProjectEuler\Renderers\GeneralRenderer;

class ShowAllRenderer extends GeneralRenderer
{
    public function renderProblems($template, $problems)
    {
        $result = '';
        foreach ($problems as $problem) {
            
            $publishDateString = date('d F Y', $problem['publish_date']);
            $publishDateTranslated = $this->translateDate($publishDateString);
            
            $result .= str_replace(
                array('{problem_id}', '{problem_body}', '{publish_date}'),
                array($problem['id'], $problem['text'], $publishDateTranslated),
                $template
            );
        }
        
        return $result;
    }
}
