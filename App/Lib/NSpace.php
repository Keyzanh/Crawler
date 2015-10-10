<?php
/**
 * Set of functions to get and manipulate namespaces.
 *
 * @class NSpace
 */
namespace App\Lib;

class NSpace {

    /**
     * Get the full namespace of the object class.
     *
     * @param Object
     * @return string
     */
    public static function GetFromObject($obj) {
    
        $class = get_class($obj);
        $namespace = substr($class, 0, strrpos($class, '\\'));
        return $namespace;
    
    }

}

?>
