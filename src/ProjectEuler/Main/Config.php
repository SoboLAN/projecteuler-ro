<?php

namespace ProjectEuler\Main;

use ProjectEuler\Main\PEException;
use ProjectEuler\Main\Logger;

/**
 * This class contains configuration options for the site.
 * @author Radu Murzea <radu.murzea@gmail.com>
 */
class Config
{
    //location of config file
    private static $configPath = 'config/main.config.json';
    
    //array containing configuration options
    private static $siteConfig;
    
    /**
     * Returns the value of the option specified by the key. The list of possible keys
     * should be in the documentation.
     * @param string $key the name of the required option.
     * @return mixed the value of the requested option or null if it doesn't exist.
     */
    public static function getValue($key)
    {
        //the configuration options must only be read once (save speed by reducing IO operations)
        if (is_null(self::$siteConfig)) {
            
            if (! is_readable(self::$configPath)) {
                $ex = new PEException('config file is inaccessible', FLPokerException::ERROR);
                Logger::log($ex->getMessage());
                throw $ex;
            }
            
            self::$siteConfig = json_decode(file_get_contents(self::$configPath), true);
            
            //json_decode returns NULL if provided string cannot be decoded
            //this almost always means a corrupt file
            if (is_null(self::$siteConfig)) {
                $ex = new PEException('config file is corrupt', FLPokerException::ERROR);
                Logger::log($ex->getMessage());
                throw $ex;
            }
        }
        
        //return option or null if invalid key was provided
        return array_key_exists($key, self::$siteConfig) ? self::$siteConfig[$key] : null;
    }
}
