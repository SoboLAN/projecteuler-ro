<?php

namespace ProjectEuler\Renderers;

/**
 * @author Radu Murzea <radu.murzea@gmail.com>
 */
abstract class GeneralRenderer
{
    /**
     * 
     * @param string $lang the new language. For now, only 'ro' is accepted
     * @return string the new date translated in the new language. If the language is invalid, an empty
     * string is returned.
     */
    protected function translateDate($date)
    {
        $newDate = '';
        if (strpos($date, 'January') !== false) {
            $newDate = str_replace('January', 'Ianuarie', $date);
            return $newDate;
        } elseif (strpos($date, 'February') !== false) {
            $newDate = str_replace('February', 'Februarie', $date);
            return $newDate;
        } elseif (strpos($date, 'March') !== false) {
            $newDate = str_replace('March', 'Martie', $date);
            return $newDate;
        } elseif (strpos($date, 'April') !== false) {
            $newDate = str_replace('April', 'Aprilie', $date);
            return $newDate;
        } elseif (strpos($date, 'May') !== false) {
            $newDate = str_replace('May', 'Mai', $date);
            return $newDate;
        } elseif (strpos($date, 'June') !== false) {
            $newDate = str_replace('June', 'Iunie', $date);
            return $newDate;
        } elseif (strpos($date, 'July') !== false) {
            $newDate = str_replace('July', 'Iulie', $date);
            return $newDate;
        } elseif (strpos($date, 'August') !== false) {
            $newDate = str_replace('August', 'August', $date);
            return $newDate;
        } elseif (strpos($date, 'September') !== false) {
            $newDate = str_replace('September', 'Septembrie', $date);
            return $newDate;
        } elseif (strpos($date, 'October') !== false) {
            $newDate = str_replace('October', 'Octombrie', $date);
            return $newDate;
        } elseif (strpos($date, 'November') !== false) {
            $newDate = str_replace('November', 'Noiembrie', $date);
            return $newDate;
        } elseif (strpos($date, 'December') !== false) {
            $newDate = str_replace('December', 'Decembrie', $date);
            return $newDate;
        }
        
        return $newDate;
    }
}
