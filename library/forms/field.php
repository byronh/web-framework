<?php

abstract class Field implements Viewable {
	
	/* * * *
	 PUBLIC
	* * * */
	
	// Constructor - initializes properties.
	
	public function __construct($label, $rules = 'optional') {
		$this->properties['label'] = $label;
		$this->properties['rules'] = $rules;
		$this->properties['maxlength'] = NULL;
		$this->properties['error'] = NULL;
		$this->properties['default'] = '';
	}
	
	// Sets the default value for this field.
	
	public function setdefault($default) {
		$this->properties['default'] = $default;
		return $this;
	}
	
	// Returns true if the input in this field passes validation, false otherwise.
	
	public function validate() {
		$rules = explode('|', $this->properties['rules']);
		foreach ($rules as $rule) {
			if (preg_match("/(.*?)\[(.*?)\]/", $rule, $match)) {
				$rule = $match[1];
				$params = explode(',', $match[2]);
				$args = array_merge(array(Request::post($this->properties['fieldname'])), $params);
			} else $args = array(Request::post($this->properties['fieldname']));
			if (!function_exists($rule)) {
				if (file_exists(ROOT.DS.'app'.DS.'controllers'.DS.'validate'.DS.$rule.'.php'))
					require(ROOT.DS.'app'.DS.'controllers'.DS.'validate'.DS.$rule.'.php');
				elseif (file_exists(ROOT.DS.'vault'.DS.'controllers'.DS.'validate'.DS.$rule.'.php'))
					require(ROOT.DS.'vault'.DS.'controllers'.DS.'validate'.DS.$rule.'.php');
			}
			$this->properties['error'] = call_user_func_array($rule, $args);
			if ($this->properties['error']) return false;
		}
		return true;
	}
	
	// Sets basic properties.
	
	public function initialize($fieldname, $ajax) {
		$this->properties['fieldname'] = $fieldname;
		$this->properties['id'] = ($ajax) ? ' id="'.$this->properties['rules'].'"' : '';
		$this->properties['value'] = (Request::server('REQUEST_METHOD') == 'POST') ? Request::post($fieldname, true): $this->properties['default'];
	}
	
	// Returns a view to be used in a form.
	
	public function getformview() {
		return new View('forms/'.strtolower(get_class($this)), $this->properties);
	}
	
	// Returns a standalone view.
	
	public function getview() {
		return $this->getformview();
	}
	
	// Returns the label of this field.
	
	public function getlabel() {
		return $this->properties['label'];
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $properties = array();
	
}

?>