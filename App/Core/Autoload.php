<?php
/**
 * Autoload handler class
 *
 * @class Autoload
 */
namespace App\Core;

class Autoload {

    /**
     * Register the autoload function
     */
    public static function Register() {
    
        spl_autoload_register(array(__CLASS__, 'Autoload'));
    
    }
    
    /**
     * Load the right php script file according to the namespace
     * of the class
     *
     * @return bool
     */
    public static function Autoload($class) {
    
        $file = str_replace('\\', DS, $class).'.php';
        if(!is_file($file) || !is_readable($file))
            return false;
        require_once($file);
        return true;
    
    }

}

?>
