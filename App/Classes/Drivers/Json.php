<?php

namespace App\Classes\Drivers;

use App\Classes\Driver;

class Json extends Driver {

    protected $content = array();

    public function addLine($array) {
    
        $this -> content[] = $array;
        return true;
    
    }
    
    public function save($file) {
    
        if(!$fd = fopen($file, 'w'))
            return false;
        $json = json_encode($this -> content);
        fwrite($fd, $json);
        fclose($fd);
        return true;
    
    }
    
}

?>
