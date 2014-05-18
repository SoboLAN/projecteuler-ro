<?php

namespace ProjectEuler\Renderers;

use ProjectEuler\Renderers\GeneralRenderer;

class StatisticsRenderer extends GeneralRenderer
{
    public function renderStatistics($template, $statistics)
    {
        return str_replace(
            array(
                '{total_text_en}',
                '{total_text_ro}',
                '{min_text_en}',
                '{min_text_ro}',
                '{max_text_en}',
                '{max_text_ro}',
                '{avg_text_en}',
                '{avg_text_ro}',
                '{total_title_en}',
                '{total_title_ro}',
                '{min_title_en}',
                '{min_title_ro}',
                '{max_title_en}',
                '{max_title_ro}',
                '{avg_title_en}',
                '{avg_title_ro}',
                '{nr_problems}',
                '{nr_problems_translated}'
                
            ),
            array(
                number_format(1.0 * $statistics['total_text_en']),
                number_format(1.0 * $statistics['total_text_ro']),
                number_format(1.0 * $statistics['min_text_en']),
                number_format(1.0 * $statistics['min_text_ro']),
                number_format(1.0 * $statistics['max_text_en']),
                number_format(1.0 * $statistics['max_text_ro']),
                number_format(1.0 * ($statistics['total_text_en'] / $statistics['nr_problems']), 2),
                number_format(1.0 * ($statistics['total_text_ro'] / $statistics['nr_problems_translated']), 2),
                number_format(1.0 * $statistics['total_title_en']),
                number_format(1.0 * $statistics['total_title_ro']),
                number_format(1.0 * $statistics['min_title_en']),
                number_format(1.0 * $statistics['min_title_ro']),
                number_format(1.0 * $statistics['max_title_en']),
                number_format(1.0 * $statistics['max_title_ro']),
                number_format(1.0 * ($statistics['total_title_en'] / $statistics['nr_problems']), 2),
                number_format(1.0 * ($statistics['total_title_ro'] / $statistics['nr_problems_translated']), 2),
                $statistics['nr_problems'],
                $statistics['nr_problems_translated']
            ),
            $template
        );
    }
}
