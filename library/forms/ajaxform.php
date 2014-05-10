<?php

class AjaxForm extends Form {
	
	/* * * *
	 PUBLIC
	* * * */
	
	// Constructor - enables ajax.
	
	public function __construct($style = 'center') {
		parent::__construct($style);
		$this->ajax = true;
	}
	
}

?>