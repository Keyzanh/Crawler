<?php

namespace App\Classes\Drivers;

use App\Classes\Driver;

class Csv extends Driver {

    protected $content = array();

    public function addLine($array) {
    
        $this -> content[] = $array;
        return true;
    
    }
    
    public function addFirstLine($array) {
    
        return $this -> addLine($array);
    
    }
    
    public function save($file) {
    
        if(!$fd = fopen($file, 'w'))
            return false;
        foreach($this -> content as $array) {
            foreach($array as $key => $value) {
                $array[$key] = str_replace(array(',', '\\'), array('\,', '\\\\'), $value);
            }
            $line = implode(',', $array);
            fwrite($fd, $line."\n");
        }
        fclose($fd);
        return true;
    
    }
    
}

?>
