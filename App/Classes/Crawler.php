<?php

namespace App\Classes;

use App\Classes\Generic;
use App\Classes\SimpleHtmlDom;

class Crawler extends Generic {

    protected $mainUrl;
    protected $informations = array();
    protected $context = array();
    
    public function beforeStart() {return true;}
    public function afterEnd() {return true;}
    public function getNextUrlToCrawl() {return false;}
    public function setContext($context) {$this -> context = $context;}
    
    protected $profileName;
    protected $conf;
    protected $currentUrl;
    protected $ready = false;
    
    public function __construct($name, $conf, $log = null) {
    
        parent::__construct();
        $this -> profileName = $name;
        $this -> conf = $conf;
        
        if(empty($conf['informations']))
            return;
        $this -> conf['informations'] = explode(',', $conf['informations']);
        
        if($log) $this -> log = $log;
            
        $this -> ready = true;
    
    }
    
    public function isReady() {return $this -> ready;}
    
    protected function loadHtml($url) {
    
        $html = new SimpleHtmlDom();
        $html -> load_file($url);
        if(!$html -> original_size) {
            $this -> logError("Cannot load $url");
            return false;
        }
        $this -> currentUrl = $url;
        return $html;
    
    }
    
    public function getInformationsFromUrl($url) {
    
        if(false === $html = $this -> loadHtml($url))
            return false;
        $infoList = $this -> conf['informations'];
        $data = array();
        foreach($infoList as $infoName) {
            if(empty($this -> informations[$infoName]))
                $data[$infoName] = null;
            else {
                $callable = array($this, $this -> informations[$infoName]);
                $content = trim(html_entity_decode(call_user_func($callable, $html), ENT_QUOTES, 'UTF-8'));
                $data[$infoName] = $content;
            }
        }
        return $data;
    
    }
    
    public function getContext() {return $this -> context;}

}

?>
