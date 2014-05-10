<?php

/* * * * * * * * *
 GLOBAL FUNCTIONS
* * * * * * * * */

// Automagically loads class files when a class is instantiated.

function __autoload($class) {
	$filename = strtolower($class).'.php';
	require(ROOT.DS.'config'.DS.'autoload.php');
	foreach ($folders as $folder => $array) {
		foreach ($array as $subfolder) {
			if (file_exists($path = ROOT.DS.$folder.DS.$subfolder.DS.$filename)) {
				require($path);
				return true;
			}
		}
	}
	return false;
}

// Constructs an instance of a class with the given constructor arguments.

function make() { // Params: classname, list of constructor params
	$args = func_get_args();
	$classname = array_shift($args);
	$reflection = new ReflectionClass($classname);
	return $reflection->newInstanceArgs($args);
}

// Returns all public properties of an object as an array.

function publicvars($object) {
	return get_object_vars($object);
}

// Returns all public properties of an object as a string.

function dump($object) {
	return '<pre>'.print_r(get_object_vars($object), true).'</pre>';
}

// Redirects instantly. Must be called before any HTML output.

function goto($url) {
	header('Location: '.$url);
	die();
}

// Handles uncaught exceptions.

function exceptionhandler(Exception $exception) {
	if (DEVELOPMENT || MAINTENANCE) {
		echo $exception;
	}
}

// Produces a nice-looking fatal error.

function fatalerror($message) {
	die('<h1>'.SITE_NAME.'</h1>
<p><strong>ERROR:</strong> '.$message.'</p>
<p>Please go back, or click <a href="'.SITE_ADDRESS.'">here</a> to return to the '.SITE_NAME.' home page.</p>');
}

// nl2br with a limit.

function nl2br_limit($string, $num) {
	$dirty = preg_replace('/\r/', '', $string);
	$clean = preg_replace('/\n{4,}/', str_repeat('<br/>', $num), preg_replace('/\r/', '', $dirty));
	return nl2br($clean);
}

?>