<?php

// Static class

class Cache {
	
	// Checks if a cache with the given name exists and is not expired.
	// Returns the cache if so, false otherwise.
	
	public static function get($name, $minutes) {
		$path = ROOT.DS.'cache'.DS.$name.'.shtml';
		if (file_exists($path) && filemtime($path) > (time() - (60 * $minutes))) {
			Debug::note('Cache hit: '.$name.', '.$minutes.' minutes');
			return unserialize(file_get_contents($path));
		}
		Debug::note('Cache miss: '.$name.', '.$minutes.' minutes');
		return false;
	}
	
	// Saves a cache with the given name.
	
	public static function set($name, $content) {
		file_put_contents(ROOT.DS.'cache'.DS.$name.'.shtml', serialize($content));
		return $content;
	}
	
	// Deletes a cache so it is forced to be regenerated.
	
	public static function clear($name) {
		$path = ROOT.DS.'cache'.DS.$name.'.shtml';
		if (file_exists($path)) unlink($path);
		Debug::note('Clearing cache '.$name);
	}
	
}

?>