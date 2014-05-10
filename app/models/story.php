<?php

class Story extends Model {
	
	public static $parents = array('User');
	public static $children = array('Link', 'Feature');
	
}

?>