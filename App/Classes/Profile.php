<?php

namespace App\Classes;

use App\Classes\Generic;
use App\Lib\NSpace;

class Profile extends Generic {

    protected $profileName;
    protected $conf;
    protected $crawler;
    protected $catalog;
    
    protected $ready = false;
    
    public function __construct($name, $conf) {
    
        parent::__construct();
        
        $this -> profileName = $name;
        $this -> conf = $conf;
        if(false === $this -> loadCrawler())
            return;
        if(false === $this -> loadCatalog())
            return;
        if(false === $this -> crawler -> isReady())
            return;
        if(false === $this -> catalog -> isReady())
            return;

        $this -> ready = true;
    
    }
    
    public function extract() {
    
        if(false === $this -> crawler -> beforeStart()) {
            $this -> logError("Error in crawler's initialization for profile ".$this -> profileName);
            return false;
        }
        
        $urls = array();
        $this -> logInfo('Getting URLs...');
        while(false !== ($url = $this -> crawler -> getNextUrlToCrawl())) {
            $urls[] = $url;
            $this -> logInfo("\t$url");
        }
        $allUrlsCounter = count($urls);
        $urls = array_unique($urls);
        $urlsCounter = count($urls);
        $this -> logInfo("$allUrlsCounter URLs found and $urlsCounter after clearing doublons");
        
        $cmpt = 0;
        foreach($urls as $url) {
            $cmpt++;
            $this -> logInfo("Crawling informations from $url ($cmpt/$urlsCounter)");
            if(false === ($informations = $this -> crawler -> getInformationsFromUrl($url))) {
                $this -> logWarning("Cannot load $url");
                continue;
            }
            $this -> catalog -> add($informations);
            $this -> logFormattedArrInfo($informations, "\t", 100);
        }
        
        $this -> logInfo('Saving catalog...');
        if(false === $this -> catalog -> save())
            $this -> logError('Cannot save catalog to '.$this -> conf['catalog']['save-to']);
    
    }
    
    public function isReady() {return $this -> ready;}
    
    protected function loadCrawler() {
    
        $conf = $this -> conf;
        if(empty($conf['crawler']['class'])) {
            $this -> logError('No crawler class specified in profile.ini for profile '.$this -> profileName);
            return false;
        }
        $class = NSpace::GetFromObject($this).'\Crawler\\'.$conf['crawler']['class'];
        $this -> crawler = new $class($this -> profileName, $conf['crawler']);
        return true;
    
    }
    
    protected function loadCatalog() {
    
        $conf = $this -> conf;
        if(empty($conf['catalog']['class'])) {
            $this -> logError('No catalog class specified in profile.ini for profile '.$this -> profileName);
            return false;
        }
        $class = NSpace::GetFromObject($this).'\Catalog\\'.$conf['catalog']['class'];
        $this -> catalog = new $class($this -> profileName, $conf['catalog']);
        return true;
    
    }
    
    
}

?>
