<?php

namespace App\Classes;

use App\Classes\Generic;

class Catalog extends Generic {

    protected $filters = array();

    protected function beforeAdd($informations) {return $informations;}

    protected $conf;
    protected $ready = false;
    protected $driver;
    
    public function __construct($name, $conf) {
    
        parent::__construct();
        $this -> profileName = $name;
        $this -> conf = $conf;
        $this -> loadDriver();
        
        if(empty($this -> conf['save-to'])) {
            $this -> logError('No destination to save the catalog specified for profile '.$this -> profileName);
            return false;
        }
        $this -> addFirstLine();
        
        $this -> ready = true;
    
    }
    
    public function add($array) {
    
        $array = $this -> beforeAdd($array);
        $array = $this -> applyFilters($array);
        return $this -> driver -> addLine($array);
    
    }
    
    public function save() {
    
        return $this -> driver -> save($this -> conf['save-to']);
    
    }
    
    public function isReady() {return $this -> ready;}
    
    protected function addFirstLine() {
    
        $conf = $this -> conf;
        if(empty($conf['first-line'])) {
            $this -> logWarning('No first line specified for profile '.$this -> profileName);
            $this -> logArrInfo($conf);
            return false;
        }
        $firstLine = explode(',', $conf['first-line']);
        return $this -> driver -> addFirstLine($firstLine);
    
    }
    
    protected function loadDriver() {
    
        $conf = $this -> conf;
        if(empty($conf['driver'])) {
            $this -> logError('No catalog driver specified for profile '.$this -> profileName);
            return false;
        }
        $class = '\App\Classes\Drivers\\'.$conf['driver'];
        $this -> driver = new $class();
    
    }
    
    protected function applyFilters($informations) {
    
        foreach($informations as $infoName => $infoValue) {
            if(empty($this -> filters[$infoName])) continue;
            foreach($this -> filters[$infoName] as $filter => $conf) {
                switch($filter) {
                    case 'default':
                        $informations[$infoName] = $this -> applyDefault($infoValue, $conf);
                        break;
                }
            }
        }
        return $informations;
    
    }
    
    protected function applyDefault($content, $conf) {
    
        if(!$content && $conf)
            $content = $conf;
        return $content;
    
    }

}

?>
