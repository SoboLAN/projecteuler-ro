<?php

namespace ProjectEuler\Main;

use ProjectEuler\Main\Config;
use ProjectEuler\Main\PEException;

/**
 * Main class of the site.
 * @author Radu Murzea <radu.murzea@gmail.com>
 */
class Site
{
    //current version of the site
    private static $siteVersion = '2.1.2';
    
    //keys represent the list of valid pages
    //the values represent the keys used in the templates. so they have a double role.
    //be careful when changing them
    private static $pages = array (
        'index.php'         => 'menu_home',
        'problems.php'      => 'menu_problems',
        'progress.php'      => 'menu_progress',
        'greasemonkey.php'  => 'menu_gm',
        'contact.php'       => 'menu_contact'
    );

    public function __construct()
    {
        if (! Config::getValue('online')) {
            throw new PEException('The site is currently down for maintenance', PEException::SITE_OFFLINE);
        }
    }
    
    /**
     * Returns the site's main template with the most important blocks already filled in. This content
     * of those blocks depends on the specified page (the title, whether or not jQuery is included etc.)
     * @param string $page the page you're currently on.
     * @return string the main template.
     */
    public function getFullPageTemplate($page)
    {
        if (! in_array($page, array_keys(self::$pages))) {
            $message = "Site::getFullPageTemplate received an invalid page: $page";
            Logger::log($message);
            throw new PEException($message, FLPokerException::ERROR);
        }
        
        $tpl = file_get_contents('templates/fullpage.tpl');
        
        $cssFile = '<link rel="stylesheet" type="text/css" href="' . Config::getValue('path_general_css') . '" />';
        $mathJAXScript = Config::getValue('enable_mathjax') ?
                            file_get_contents('templates/mathjax.tpl') :
                            '';
        $analyticsScript = Config::getValue('enable_google_analytics') ?
                            file_get_contents('templates/google_analytics.tpl') :
                            '';
        
        $tpl = str_replace(
            array('{css_file}', '{mathjax_script}', '{google_analytics_script}', '{site_version}'),
            array($cssFile, $mathJAXScript, $analyticsScript, self::$siteVersion),
            $tpl
        );
        
        foreach (self::$pages as $key => $value) {
            $selected = ($page == $key) ? 'class="selected"' : '';
            $tpl = str_replace('{selected_' . $value . '}', $selected, $tpl);
        }

        return $tpl;
    }
    
    /**
     * Utility function that tells you whether the specified ID is valid.
     * A valid ID is made up only of digits, maximum 4 of them.
     * @param int $id the ID.
     * @return bool true if the specified ID is valid, false otherwise.
     */
    public function isValidID($id)
    {
        return (strlen($id) <= 4 && ctype_digit($id));
    }
}
