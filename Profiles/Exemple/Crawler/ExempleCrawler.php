<?php

namespace Profiles\Exemple\Crawler;

use App\Classes\Crawler;
use App\Lib\Url;

class ExempleCrawler extends Crawler {

    protected $mainUrl = 'http://www.blumen.fr';
    protected $informations = array(
        //'name' => 'getName'
    );
    
    public function getNextUrlToCrawl() {return false;}

}

?>
