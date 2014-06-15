<?php

namespace ProjectEuler\Renderers;

use ProjectEuler\Renderers\GeneralRenderer;

class ProblemRenderer extends GeneralRenderer
{
    public function renderProblem($mainTemplate, $prevTpl, $nextTpl, $content, $neighBorsStatus)
    {
        $nextContent = '';
        if (isset($neighBorsStatus['next']) && $neighBorsStatus['next'] == true) {
            
            $nextContent = str_replace(
                array('{next_id}', '{next_image}'),
                array($content['id'] + 1, 'icon_next_solved.png'),
                $nextTpl
            );
        }
        
        $prevContent = '';
        if (isset($neighBorsStatus['prev']) && $neighBorsStatus['prev'] == true) {
            
            $prevContent = str_replace(
                array('{previous_id}', '{previous_image}'),
                array($content['id'] - 1, 'icon_back_solved.png'),
                $prevTpl
            );
        }
        
        $publishDateString = date('d F Y', $content['publish_date']);
        $publishDateTranslated = $this->translateDate($publishDateString);
        
        $result = str_replace(
            array(
                '{problem_id}',
                '{problem_title}',
                '{problem_body}',
                '{problem_publish_date}',
                '{previous_link}',
                '{next_link}'
            ),
            array(
                $content['id'],
                $content['title_romanian'],
                $content['text_romanian'],
                $publishDateTranslated,
                $prevContent,
                $nextContent
            ),
            $mainTemplate
        );
        
        return $result;
    }
    
    public function renderTags($tagBoxTpl, $tagTpl, $tags)
    {
        if (empty($tags)) {
            return '';
        }
        
        $renderedTags = '';
        foreach ($tags as $tag) {
            $renderedTags .= str_replace(
                array('{tag_id}', '{tag_name}'),
                array($tag['id'], $tag['name']),
                $tagTpl
            );
        }
        
        $result = str_replace('{tags}', $renderedTags, $tagBoxTpl);
        
        return $result;
    }
}
