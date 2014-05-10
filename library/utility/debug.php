<?php

// Static class

class Debug {
	
	// Adds information to the Debug log.
	
	public static function note($info) {
		if (DEVELOPMENT || MAINTENANCE) {
			self::$info[] = $info;
		}
	}
	
	// Outputs the Debug log and information about the current script execution.
	
	public static function dump() {
		if (DEVELOPMENT || MAINTENANCE) {
			echo "<pre><strong>Debug log:</strong>\n\n";
			foreach (self::$info as $info) {
				echo $info."\n";
			}
			echo "\nScript execution time: ".round((microtime(true) - $GLOBALS['script_start_time']) * 1000, 3)." ms\n";
			echo 'Memory usage: '.round(memory_get_usage()/1024,2)." kb\n";
			echo 'Peak memory usage: '.round(memory_get_peak_usage()/1024,2)." kb\n</pre>";
		}
	}
	
	
	/* * * *
	 PRIVATE
	* * * */
	
	private static $info = array();
	
}

?>