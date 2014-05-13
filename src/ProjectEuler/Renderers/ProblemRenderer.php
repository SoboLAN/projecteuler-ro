<?php

namespace ProjectEuler\Renderers;

use ProjectEuler\Renderers\GeneralRenderer;

class ProblemRenderer extends GeneralRenderer
{
    public function renderProblem($template, $content, $neighBorsStatus)
    {
        if (empty ($content)) {
            return '';
        }
        
        $result = str_replace(
            array(
                '{problem_id}',
                '{problem_title}',
                '{problem_body}',
                '{problem_publish_date}'
            ),
            array(
                $content['id'],
                $content['title_romanian'],
                $content['text_romanian'],
                $content['publish_date']
            ),
            $template
        );
        
        return $result;
    }
    
    public function renderTags($template, $content)
    {
        if (empty ($content)) {
            return '';
        }
        
        $renderedTags = '';
        foreach ($content as $tag) {
            $renderedTags .= str_replace(
                array('{tag_id}', '{tag_name}'),
                array($tag['id'], $tag['name']),
                $template
            );
        }
        
        return $renderedTags;
    }
}
