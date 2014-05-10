<?php

// Static class

class Inflector {
	
	// Converts a plural word to a singular word.
	
	public static function singular($str) {
		$str = trim($str);
		$end = substr($str, -3);
		if ($end == 'ies') $str = substr($str, 0, strlen($str)-3).'y';
		elseif ($end == 'ses') $str = substr($str, 0, strlen($str)-2);
		else {
			$end = substr($str, -1);
			if ($end == 's') $str = substr($str, 0, strlen($str)-1);
		}
		return $str;
	}
	
	// Converts a singular word to a plural word.
	
	public static function plural($str) {
		$str = trim($str);
		$end = substr($str, -1);
		if ($end == 'y') {
			$vowels = array('a', 'e', 'i', 'o', 'u');
			$str = in_array(substr($str, -2, 1), $vowels) ? $str.'s' : substr($str, 0, -1).'ies';
		} else $str .= 's';
		return $str;
	}
	
	// Converts underscore or space-separated words to CamelCase.
	
	public static function camelcase($str) {
		$str = 'x'.strtolower(trim($str));
		$str = ucwords(preg_replace('/[\s_]+/', ' ', $str));
		return substr(str_replace(' ', '', $str), 1);
	}
	
	// Converts an incorrectly capitalized name to a correctly capitalized one.
	
	public static function ucname($str) {
		$str = ucwords(strtolower($str));
		foreach (array('-', '\'') as $delimiter) {
			if (strpos($str, $delimiter) !== false) {
				$str =implode($delimiter, array_map('ucfirst', explode($delimiter, $str)));
			}
		}
		return $str;
	}
	
	// Converts space-separated words to underscore-separated words.
	
	public static function underscore($str) {
		return preg_replace('/[\s]+/', '_', strtolower(trim($str)));
	}
	
	// Converts a string with underscores to a readable string.
	
	public static function humanize($str) {
		return ucwords(preg_replace('/[_]+/', ' ', strtolower(trim($str))));
	}
	
}

?>