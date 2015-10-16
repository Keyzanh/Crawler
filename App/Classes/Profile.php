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
    protected $context = array(
        'urls' => array()
    );
    
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
        
        $this -> _loadContext();
        
        $this -> ready = true;
    
    }
    
    public function extract() {
    
        $timeBefore = Date::Now();
        if(false === $this -> crawler -> beforeStart()) {
            $this -> logError("Error in crawler's initialization for profile ".$this -> profileName);
            return false;
        }

        $this -> logInfo('Getting URLs from the crawler...');
        while(false !== ($url = $this -> crawler -> getNextUrlToCrawl())) {
            $this -> context['urls'][] = $url;
            $this -> logInfo("    $url");
            if(count($this -> context['urls']) % 10 == 0) $this -> _saveContext();
        }
        $this -> _saveContext();
        $allUrlsCounter = count($this -> context['urls']);
        $this -> context['urls'] = array_unique($this -> context['urls']);
        $urlsCounter = count($this -> context['urls']);
        $this -> logInfo("$allUrlsCounter URLs found and $urlsCounter after clearing doublons");
        
        $cmpt = 0;
        foreach($this -> context['urls'] as $url) {
            $cmpt++;
            $this -> logInfo("Crawling informations from $url ($cmpt/$urlsCounter)");
            if(false === ($informations = $this -> crawler -> getInformationsFromUrl($url)))
                continue;
            $informations = $this -> catalog -> add($informations);
            $this -> logFormattedArrInfo($informations, "    ", 100);
        }
        
        $this -> logInfo('Saving catalog...');
        if(false === $this -> catalog -> save())
            $this -> logError('Cannot save catalog to '.$this -> conf['catalog']['save-to']);
        $timeAfter = Date::Now();
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
        $this -> crawler = new $class($this -> profileName, $conf['crawler'], $this -> log);
        return true;
    
    }
    
    protected function _loadCatalog() {
    
        $conf = $this -> conf;
        if(empty($conf['catalog']['class'])) {
            $this -> logError('No catalog class specified in profile.ini for profile '.$this -> profileName);
            return false;
        }
        $class = NSpace::GetFromObject($this).'\Catalog\\'.$conf['catalog']['class'];
        $this -> catalog = new $class($this -> profileName, $conf['catalog'], $this -> log);
        return true;
    
    }
    
    protected function _configureLog() {
    
        if(!empty($this -> conf['general']['log-file']))
            $this -> log -> addInfoOutput($this -> conf['general']['log-file']);
        if(!empty($this -> conf['general']['log-error-file'])) {
            $this -> log -> addWarningOutput($this -> conf['general']['log-error-file']);
            $this -> log -> addErrorOutput($this -> conf['general']['log-error-file']);
        }
    
    }
    
    protected function _loadContext() {
    
        if(empty($this -> conf['general']['save-progress-to']))
            return;
    
        $ctxFile = $this -> conf['general']['save-progress-to'];
        if(!is_file($ctxFile) || !is_readable($ctxFile))
            return false;
        $this -> logInfo('Restoring context...');
        $context = file_get_contents($ctxFile);
        $context = json_decode($context, true);
        if($context === null) return false;
        $this -> context = $context['profile'];
        $this -> crawler -> setContext($context['crawler']);
        $this -> logInfo('Restored URLs:');
        foreach($this -> context['urls'] as $url) $this -> logInfo("    $url");
        return true;
    
    }
    
    protected function _saveContext() {
    
        if(empty($this -> conf['general']['save-progress-to']))
            return;
    
        if(!$fd = fopen($this -> conf['general']['save-progress-to'], 'w'))
            return false;
        $context = array(
            'profile' => $this -> context,
            'crawler' => $this -> crawler -> getContext()
        );
        $context = json_encode($context);
        fwrite($fd, $context);
        fclose($fd);
        return true;
    
    }
    
}

?>
