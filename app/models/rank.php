<?php

class Rank extends Model {
	
	public static $parents = array();
	public static $children = array('User', 'AdminFunction');
	
	public function __toString() {
		return $this->Rank_name;
	}
	
}

?>