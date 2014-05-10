<?php

class TextArea extends Field {
	
	/* * * *
	 PUBLIC
	* * * */
	
	public function __construct($label, $rules = 'optional', $rows = 26, $cols = 123) {
		parent::__construct($label, $rules);
		$this->properties['rows'] = $rows;
		$this->properties['cols'] = $cols;
	}
	
}

?>