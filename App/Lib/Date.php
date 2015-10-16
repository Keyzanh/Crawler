<?php

namespace App\Lib;

class Date {

    public static function Now() {
    
        return time();
    
    }
    
    public static function MicroNow() {
    
        return microtime();
    
    }
    
    public static function TimestampToString($time) {
    
        $string = '';
        
        $secs = $time % 60;
        if($secs) $string = $secs.'s';
        $time -= $secs; $time /= 60;
        
        $mins = $time % 60;
        if($mins) $string = $mins.'min '.$string;
        $time -= $mins; $time /= 60;
        
        $hours = $time;
        if($hours) $string = $hours.'h '.$string;
        
        return $string;
    
    }
    
    public static function TimestampToShortDateTime($time) {
    
        return date('d-M-Y:H:i:s', $time);
    
    }

}

?>
