<?php

namespace ProjectEuler\Renderers;

/**
 * @author Radu Murzea <radu.murzea@gmail.com>
 */
class PaginationRenderer
{    
    public function render($paginationTpl, $currentLinkTpl, $pageLinkTpl, $content, $tags)
    {
        $elements = array();
        for ($i = 0; $i < count($content); $i++) {
            
            $page = $content[$i]['page'];
            
            switch($content[$i]['type']) {
                case 'normal':
                case 'current':
                    $text = $content[$i]['page'];
                    break;
                    
                case 'prev':
                    $text = 'Precedenta';
                    break;
                    
                case 'next':
                    $text = 'Următoarea';
                    break;
            }
            
            if ($content[$i]['type'] == 'current') {
                $elements[$i] = str_replace(array('{title}', '{page}'), array($text, $page), $currentLinkTpl);
            } else {
                $tagsList = '';
                if (count($tags) > 0) {
                    $tagsList .= '&amp;tag' . implode('=1&amp;tag', $tags) . '=1';
                }
                
                $elements[$i] = str_replace(
                    array('{title}', '{page}', '{tags}'),
                    array($text, $page, $tagsList),
                    $pageLinkTpl
                );
            }
        }
        
        return str_replace('{pages}', implode("\n", $elements), $paginationTpl);
    }
}