<?php

class Session {
	
	// Constructor - begins session.
	
	protected function __construct() {
		session_start();
	}
	
	
	/* * * * * * * *
	 SESSION CONTROL
	* * * * * * * */
	
	// Generates a session token to prevent CSRF.
	
	public function newcsrftoken() {
		$token = strrev(hash('sha256', '_'.uniqid().'.[973[]);$wyq%^rice'));
		$this->_csrf = $token;
		return $token;
	}
	
	// Closes the current session and destroys the session cookie.
	
	public function terminate() {
		$_SESSION = NULL;
		session_destroy();
		Cookie::delete('PHPSESSID');
	}
	
	
	/* * * * * * *
	 MAGIC METHODS
	* * * * * * */
	
	public function __get($name) {
		return $_SESSION[$name];
	}
	
	public function __set($name, $value) {
		$_SESSION[$name] = $value;
		return $value;
	}
	
	public function __isset($name) {
		return isset($_SESSION[$name]);
	}
	
	public function __unset($name) {
		unset($_SESSION[$name]);
	}	
	
}

?>