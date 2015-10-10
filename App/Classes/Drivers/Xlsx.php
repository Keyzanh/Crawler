<?php

namespace App\Classes\Drivers;

use App\Classes\Driver;
use App\Classes\XlsxWriter;

class Xlsx extends Driver {

    protected $workbook;
    
    public function __construct() {
    
        parent::__construct();
        $this -> workbook = new XlsxWriter();
    
    }

    public function addLine($array) {
    
        $this -> workbook -> writeSheetRow('Sheet1', $array);
        return true;
    
    }
    
    public function addFirstLine($array) {
    
        return $this -> addLine($array);
    
    }
    
    public function save($file) {
    
        $this -> workbook -> writeToFile($file);
        return true;
    
    }
    
}

?>
