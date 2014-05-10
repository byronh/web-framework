<?php

// Static class

class Factory {
	
	// Retrieves the instance of a class if it exists, otherwise creates a new one.
	// Should only be used for singleton classes.
	
	public static function get($classname) {
		if (!isset(self::$$classname)) {
			Debug::note('Factory: Instantiating '.$classname);
			self::$$classname = new $classname;
			
		}
		return self::$$classname;
	}
	
	// Substitutes an instance of a class with the given object.
	// Should only be used for singleton classes.
	
	public static function set($classname, $substitute) {
		self::$$classname = $substitute;
		return self::$$classname;
	}
	
	// Returns true if the given singleton has already been instantiated.
	
	public static function loaded($classname) {
		return isset(self::$$classname);
	}
	
	
	/* * * *
	 PRIVATE
	* * * */
	
	private static $Database, $Session;
	
}

?>