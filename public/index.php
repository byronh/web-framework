<?php

/* * * * * * * *
 INITIALIZATION
* * * * * * * */

$script_start_time = microtime(true);

ob_start('ob_gzhandler');

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

require(ROOT.DS.'config'.DS.'global.php');
require(ROOT.DS.'library'.DS.'helpers'.DS.'global.php');

if (DEVELOPMENT || MAINTENANCE) {
	error_reporting(E_ALL | E_STRICT);
}

header('Content-Type:text/html; charset=UTF-8');
date_default_timezone_set('Canada/Pacific');

set_exception_handler('exceptionhandler');

$router = new Router();
if (isset($_GET['_url'])) $router->route($_GET['_url']);
else $router->route();

ob_end_flush();

?>