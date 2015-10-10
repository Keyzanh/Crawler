<?php

namespace App\Classes;

use App\Core\Log;

class Generic {

    protected $log;

    public function __construct() {
    
        $this -> log = new Log();
    
    }
    
    protected function logInfo($content) {
    
        return $this -> log -> info($content);
    
    }
    
    protected function logArrInfo($content) {
    
        $str = print_r($content, true);
        return $this -> logInfo($str);
    
    }
    
    protected function logFormattedArrInfo($array, $prepend = '', $maxLength = 0) {
    
        $keys = array_keys($array);
        $maxLen = 0;
        foreach($keys as $key)
            if(strlen($key) > $maxLen) $maxLen = strlen($key);
            
        foreach($array as $key => $value) {
            $key .= ':';
            for($i=strlen($key); $i <= $maxLen; $i++)
                $key .= ' ';
            if($maxLength) $value = mb_strimwidth($value, 0, $maxLength, '...');
            $this -> logInfo("$prepend$key $value");
        }
    
    }
    
    protected function logWarning($content) {
    
        return $this -> log -> warning($content);
    
    }
    
    protected function logArrWarning($content) {
    
        $str = print_r($content, true);
        return $this -> logWarning($str);
    
    }
    
    protected function logFormattedArrWarning($array, $prepend = '', $maxLength = 0) {
    
        $keys = array_keys($array);
        $maxLen = 0;
        foreach($keys as $key)
            if(strlen($key) > $maxLen) $maxLen = strlen($key);
            
        foreach($array as $key => $value) {
            $key .= ':';
            for($i=strlen($key); $i <= $maxLen; $i++)
                $key .= ' ';
            if($maxLength) $value = mb_strimwidth($value, 0, $maxLength, '...');
            $this -> logWarning("$prepend$key $value");
        }
    
    }
    
    protected function logError($content) {
    
        return $this -> log -> error($content);
    
    }
    
    protected function logArrError($content) {
    
        $str = print_r($content, true);
        return $this -> logError($str);
    
    }
    
    protected function logFormattedArrError($array, $prepend = '', $maxLength = 0) {
    
        $keys = array_keys($array);
        $maxLen = 0;
        foreach($keys as $key)
            if(strlen($key) > $maxLen) $maxLen = strlen($key);
            
        foreach($array as $key => $value) {
            $key .= ':';
            for($i=strlen($key); $i <= $maxLen; $i++)
                $key .= ' ';
            if($maxLength) $value = mb_strimwidth($value, 0, $maxLength, '...');
            $this -> logError("$prepend$key $value");
        }
    
    }

}

?>
