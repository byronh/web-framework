<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(dirname(__FILE__))));

require(ROOT.DS.'config'.DS.'global.php');
require(ROOT.DS.'library'.DS.'helpers'.DS.'curl.php');

echo curlpost(SITE_ADDRESS.'/cron/'.$action, array('curl_pass' => CURL_PASS));

?>