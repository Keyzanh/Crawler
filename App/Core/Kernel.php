<?php
/**
 * The application handler class
 *
 * @class Kernel
 */
namespace App\Core;

use App\Core\Log;
use App\Lib\ParserIni;

class Kernel {

    /**
     * The profiles configuration file path
     */
    const CONF_PROFILES_FILE = 'App'.DS.'Conf'.DS.'profiles.ini';

    /**
     * The log handler object
     *
     * @var Log
     */
    protected static $log;
    
    /**
     * The command line parameters
     *
     * @var array
     */
    protected static $argv;
    
    /**
     * The configured profiles list
     *
     * @var array
     */
    protected static $profiles;
    
    /**
     * Initialize the application by loading the log handler and
     * all the configurations files
     *
     * @param array
     */
    public static function Init($argv) {
    
        self::$argv = $argv;
        self::$log = new Log();
        
        self::$profiles = ParserIni::Parse(self::CONF_PROFILES_FILE);
        if(self::$profiles === false) {
            self::LogError('Cannot load the general profiles configuration file ('.self::CONF_PROFILES_FILE.')');
            die();
        }
    
    }
    
    /**
     * Start the execution of the application
     */
    public static function Start() {
    
        $profile = self::LoadProfile(self::$argv[1]);
        if(!$profile -> isReady())
            return false;
        $profile -> extract();
    
    }
    
    /**
     * Log an informative message
     *
     * @return bool
     */
    public static function LogInfo($content) {
    
        return self::$log -> info($content);
    
    }
    
    /**
     * Log a warning message
     *
     * @return bool
     */
    public static function LogWarning($content) {
    
        return self::$log -> warning($content);
    
    }
    
    /**
     * Log an error message
     *
     * @return bool
     */
    public static function LogError($content) {
    
        return self::$log -> error($content);
    
    }
    
    /**
     * Load the required profile class
     *
     * @param string
     * @param Profile
     */
    protected static function LoadProfile($name) {
    
        if(empty(self::$profiles[$name])) {
            self::LogError("Unknown profile $name");
            return false;
        }
        $confFile = 'Profiles'.DS.self::$profiles[$name].DS.'profile.ini';
        $conf = ParserIni::Parse($confFile);
        if($conf === false) {
            self::LogError("Cannot load profile configuration file ($confFile)");
            return false;
        }
        if(empty($conf['general']['class'])) {
            self::LogError("No class specified in $confFile");
            return false;
        }
        $class = 'Profiles\\'.self::$profiles[$name].'\\'.$conf['general']['class'];
        return new $class($name, $conf);
    
    }

}

?>
