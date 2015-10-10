<?php

namespace App\Classes;

use App\Classes\Generic;
use App\Classes\SimpleHtmlDom;

class Crawler extends Generic {

    protected $mainUrl;
    protected $informations = array();
    
    public function beforeStart() {return true;}
    public function getNextUrlToCrawl() {return false;}
    
    protected $profileName;
    protected $conf;
    protected $currentUrl;
    protected $ready = false;
    
    public function __construct($name, $conf) {
    
        parent::__construct();
        $this -> profileName = $name;
        $this -> conf = $conf;
        
        if(empty($conf['informations']))
            return;
        $this -> conf['informations'] = explode(',', $conf['informations']);
            
        $this -> ready = true;
    
    }
    
    public function isReady() {return $this -> ready;}
    
    protected function loadHtml($url) {
    
        $html = new SimpleHtmlDom();
        $html -> load_file($url);
        if(!$html -> original_size) return false;
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

}

?>
