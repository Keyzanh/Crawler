<?php
/**
 * Set of functions to manipulate INI files.
 *
 * @class ParserIni
 */
namespace App\Lib;

class ParserIni {

    /**
     * Get the content of the INI file in an array.
     * The array will follow the sections of the file.
     *
     * @param string
     * @return array
     */
    public static function Parse($file) {
    
        if(!is_file($file) || !is_readable($file))
            return false;
        return parse_ini_file($file, true);
    
    }

}

?>
