#! /usr/bin/php
<?php

require_once('App'.DIRECTORY_SEPARATOR.'Core'.DIRECTORY_SEPARATOR.'Constants.php');
require_once('App'.DS.'Core'.DS.'Autoload.php');

use App\Core\Autoload;
use App\Core\Error;
use App\Core\Kernel;

Autoload::Register();
Error::Register();
Kernel::Init($argv);

Kernel::Start();

?>
