<?php

// Static class

class Date {
	
	// Converts a Unix timestamp to a short representation.
	// eg. "May 17, 10:10 pm"
	
	public static function short($time) {
		$date = getdate($time);
		if ($date['hours'] >= 12) $meridiem = ' pm'; else $meridiem = ' am';
		if ($date['hours'] > 12) $date['hours'] -= 12;
		elseif ($date['hours'] == 0) $date['hours'] = 12;
		if ($date['minutes'] < 10) $date['minutes'] = '0'.$date['minutes'];
		return substr($date['month'],0,3).' '.$date['mday'].', '.$date['hours'].':'.$date['minutes'].$meridiem;
	}
	
	// Converts a Unix timestamp to a long representation.
	// eg. "Monday, May 17, 2010 10:10 pm"
	
	public static function long($time) {
		$date = getdate($time);
		if ($date['hours'] >= 12) $meridiem = ' pm'; else $meridiem = ' am';
		if ($date['hours'] > 12) $date['hours'] -= 12;
		elseif ($date['hours'] == 0) $date['hours'] = 12;
		if ($date['minutes'] < 10) $date['minutes'] = '0'.$date['minutes'];
		return $date['weekday'].', '.$date['month'].' '.$date['mday'].', '.$date['year'].' '.$date['hours'].':'.$date['minutes'].$meridiem;
	}
	
	// Converts a Unix timestamp to a time difference from the current time.
	// eg. "2 Minutes ago"
	
	public static function ago($time) {
		if (empty($time)) return "A long time ago";
		$periods = array("second", "minute", "hour", "day", "week", "month", "year");
		$lengths = array("60", "60", "24", "7", "4.35", "12", "1");
		$difference = time() - $time;
		
		for ($i = 0; $difference >= $lengths[$i] && $i < count($lengths)-1; $i++)
			$difference /= $lengths[$i];
		
		$difference = round($difference);
		if ($difference != 1) $periods[$i].= 's';
		return "$difference $periods[$i] ago";
	}
	
	// Converts a Unix timestamp to a month day representation.
	// eg. "May 17, 2010"
	
	public static function day($time) {
		$date = getdate($time);
		return $date['month'].' '.$date['mday'].', '.$date['year'];
	}
	
	// Converts a Unix timestamp to an RSS-compatible RFC-822 representation.
	// eg. "Wed, 02 Oct 2002 13:00:00 GMT"
	
	public static function rss($time) {
		return date('r', $time);
	}
	
}

?>