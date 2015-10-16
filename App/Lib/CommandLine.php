<?php

namespace App\Lib;

use App\Lib\ParserIni;

class CommandLine {

    protected static $parameters  =array(
        '--quiet' => 0,
        '--stfu' => 0
    );

    public static function Parse($argv) {
    
        $argc = count($argv);
        $cmpt = 1;
        $parameters = array();
        while($cmpt < $argc) {
            $param = $argv[$cmpt];
            if(substr($param, 0, 1) == '-') {
                
            } else {
                $parameters[] = $param;
                $cmpt++;
            }
        }
    
    }
    
    protected static function _

}

?>
