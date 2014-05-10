<?php

if (!defined('ROOT')) die('Direct include not allowed.');

/* * * * * * * *
 AUTOLOAD CONFIG
* * * * * * * */

$folders = array(
	'app' => array('models', 'controllers', 'classes'),
	'library' => array('core', 'utility', 'forms', 'plugins', 'exceptions'),
	'vault' => array('controllers', 'models')
);

?>