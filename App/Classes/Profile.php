<?php

namespace App\Classes;

use App\Classes\Generic;
use App\Lib\NSpace;
use App\Lib\Date;

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
        if(false === $this -> _loadCrawler())
            return;
        if(false === $this -> _loadCatalog())
            return;
        if(false === $this -> crawler -> isReady())
            return;
        if(false === $this -> catalog -> isReady())
            return;

        $this -> _configureLog();        
        $this -> ready = true;
    
    }
    
    public function extract() {
    
        $timeBefore = Date::CurrentTimestamp();
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
            $informations = $this -> catalog -> add($informations);
            $this -> logFormattedArrInfo($informations, "\t", 100);
        }
        
        $this -> logInfo('Saving catalog...');
        if(false === $this -> catalog -> save())
            $this -> logError('Cannot save catalog to '.$this -> conf['catalog']['save-to']);
        $timeAfter = Date::CurrentTimestamp();
        $elapsedTime = $timeAfter - $timeBefore;
        $this -> logInfo('Operation took '.Date::TimestampToString($elapsedTime));
    
    }
    
    public function isReady() {return $this -> ready;}
    
    protected function _loadCrawler() {
    
        $conf = $this -> conf;
        if(empty($conf['crawler']['class'])) {
            $this -> logError('No crawler class specified in profile.ini for profile '.$this -> profileName);
            return false;
        }
        $class = NSpace::GetFromObject($this).'\Crawler\\'.$conf['crawler']['class'];
        $this -> crawler = new $class($this -> profileName, $conf['crawler']);
        return true;
    
    }
    
    protected function _loadCatalog() {
    
        $conf = $this -> conf;
        if(empty($conf['catalog']['class'])) {
            $this -> logError('No catalog class specified in profile.ini for profile '.$this -> profileName);
            return false;
        }
        $class = NSpace::GetFromObject($this).'\Catalog\\'.$conf['catalog']['class'];
        $this -> catalog = new $class($this -> profileName, $conf['catalog']);
        return true;
    
    }
    
    protected function _configureLog() {
    
        if(!empty($this -> conf['general']['log-file']))
            $this -> log -> addInfoOutput($this -> conf['general']['log-file']);
        if(!empty($this -> conf['general']['log-error-file'])) {
            $this -> log -> addWarningOutput($this -> conf['general']['log-error-file']);
            $this -> log -> addWarningOutput($this -> conf['general']['log-error-file']);
        }
    
    }
    
}

?>
