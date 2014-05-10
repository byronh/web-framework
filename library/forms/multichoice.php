<?php

abstract class MultiChoice extends Field {
	
	/* * * *
	 PUBLIC
	* * * */
	
	public function __construct($label, $rules = 'optional', $choices = array()) {
		parent::__construct($label, $rules, $choices);
		$this->properties['choices'] = $choices;
	}
	
}

?>