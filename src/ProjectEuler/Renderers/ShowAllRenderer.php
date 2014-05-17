<?php

namespace ProjectEuler\Renderers;

use ProjectEuler\Renderers\GeneralRenderer;

class ShowAllRenderer extends GeneralRenderer
{
    public function renderProblems($template, $problems)
    {
        $result = '';
        foreach ($problems as $problem) {
            $result .= str_replace(
                array(
                    '{problem_id}',
                    '{problem_body}',
                    '{publish_date}'
                ),
                array(
                    $problem['id'],
                    $problem['text'],
                    $problem['publish_date']
                ),
                $template
            );
        }
        
        return $result;
    }
}
