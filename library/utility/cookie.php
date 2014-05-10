<?php

// Static class

require(ROOT.DS.'config'.DS.'cookie.php');

class Cookie {
	
	// Gets the value of the cookie with the given name.
	
	public static function get($name) {
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : NULL;
	}
	
	// Sets a cookie with the given name and value.
	
	public static function set($name, $value) {
		setcookie($name, $value, time() + COOK_DURATION, COOK_PATH, COOK_DOMAIN, COOK_SECURE, COOK_HTTP);
	}
	
	// Unsets a cookie.
	
	public static function delete($name) {
		setcookie($name, '', time() - 3600, COOK_PATH, COOK_DOMAIN, COOK_SECURE, COOK_HTTP);
	}
	
	// Checks whether or not a cookie has been set.
	
	public static function exists($name) {
		return isset($_COOKIE[$name]);
	}
	
}

?>