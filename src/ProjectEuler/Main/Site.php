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
    private static $siteVersion = '1.2.0';

    public function __construct()
    {
        if (! Config::getValue('online')) {
            throw new PEException('The site is currently down for maintenance', PEException::SITE_OFFLINE);
        }
    }
    
    /**
     * Utility function that tells you whether the specified ID is valid.
     * A valid ID is made up only of digits, maximum 4 of them.
     * @param int $id the ID.
     * @return bool true if the specified ID is valid, false otherwise.
     */
    public function isValidID($id)
    {
        return (strlen($id) <= 4 and ctype_digit($id));
    }
}
