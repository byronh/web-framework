<?php

class TextBox extends Field {
	
	/* * * *
	 PUBLIC
	* * * */
	
	public function __construct($label, $rules = 'optional') {
		parent::__construct($label, $rules);
		foreach (explode('|', $rules) as $rule) {
			if (preg_match("/(.*?)\[(.*?)\]/", $rule, $match)) {
				if ($match[1] != 'maxlength') continue;
				$this->properties['maxlength'] = ' maxlength="'.$match[2].'"';
			}
		}
	}
	
}

?>