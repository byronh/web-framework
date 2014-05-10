<?php

class Comment extends Model {
	
	public static $parents = array('User', 'Story');
	public static $children = array();
	
}

?>