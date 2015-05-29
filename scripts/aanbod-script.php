<?php

$time_start = microtime(true);
echo 'script in progress.......' . PHP_EOL;

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Composer autoload */
require_once realpath(APPLICATION_PATH . '/../vendor/autoload.php');

require_once '/home/isaac/vhosts/ICTLAB/application/models/FundaApiConnector.php';
require_once '/home/isaac/vhosts/ICTLAB/application/models/Funda/Aanbod.php';

$aanbod = new Application_Model_Funda_Aanbod();
$params = array(
    "filters" => array(
        "city" => "heel-nederland"
    ),

);

$i = 1;

for ($page = 1; $page < 5000; $page++) {
    $aanbod->page = $page;
    $collection = $aanbod->getCollection($params);

    foreach ($collection as $house) {
        echo "Id: " .$house->Id . PHP_EOL;
        $i++;
    }
}

echo PHP_EOL;
echo 'script done' . PHP_EOL;

$time_end = microtime(true);

$execution_time = $time_end - $time_start;

echo 'Script executed in ' . "$execution_time seconds with $i objects" . PHP_EOL;
