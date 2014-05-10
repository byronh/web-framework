<?php

class Token extends Model {
	
	public static $parents = array('User');
	public static $children = array();
	
	public function generate() {
		$this->Token_time = time();
		$this->Token_value = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand(0,0xffff),mt_rand(0,0xffff),
			mt_rand(0,0xffff),
			mt_rand(0,0x0fff)|0x4000,
			mt_rand(0,0x3fff)|0x8000,
			mt_rand(0,0xffff),mt_rand(0,0xffff),mt_rand(0,0xffff)
		);
		return $this->Token_value;
	}
	
}

?>