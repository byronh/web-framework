<?php

class Form implements Viewable {
	
	/* * * *
	 PUBLIC
	* * * */
	
	public $Buttons = array(), $Hiddens = array(), $Fields = array();
	
	// Constructor - sets properties.
	
	public function __construct($style = 'center') {
		$this->Session = Factory::get('Session');
		$this->submitted = Request::server('REQUEST_METHOD') == 'POST';
		$this->style = $style;
		$this->add(new Hidden('_csrf', $this->Session->_csrf));
	}
	
	// Validates form if submitted, displays form otherwise.
	// Returns true if all elements passed validation, false otherwise.
	
	public function handle() {
		if ($this->submitted) {
			if ($this->Session->loggedin() && Request::post('_csrf') != $this->Session->_csrf) {
				Debug::note('CSRF Validation failed');
				$Log = new Log();
				$Log->logcsrf(array(
					'User' => $this->Session->getUser()->User_name,
					'Token' => Request::post('_csrf')
				));
				return false;
			}
			if ($this->validate()) {
				$this->render = false;
				return true;
			}
		}
		return false;
	}
	
	// Adds new form elements. Optional default parameter.
	
	public function add($element, $default = NULL) {
		if ($element instanceof Button) {
			$this->Buttons[] = $element;
		} elseif ($element instanceof Hidden) {
			$this->Hiddens[] = $element;
		} elseif ($element instanceof Field) {
			if ($default !== NULL) $element->setdefault($default);
			$element->initialize('field'.count($this->Fields), $this->ajax);
			$this->Fields[] = $element;
		}
		return $element;
	}
	
	// Retrieves the value of an input field.
	
	public function input($field = NULL) {
		if (is_numeric($field)) {
			return Request::post('field'.$field);
		} elseif ($field === NULL) {
			$numfields = count($this->Fields);
			$inputs = array();
			for ($i=0; $i<$numfields; $i++) {
				$inputs[] = Request::post('field'.$i);
			}
			return $inputs;
		} else {
			foreach ($this->Fields as $index => $testfield) {
				if ($testfield->getlabel() == $field) {
					return Request::post('field'.$index);
				}
			}
			return 'Field label not found.';
		}
	}
	
	// Accesses the value of a hidden field.
	
	public function hidden($hiddenname) {
		return Request::post($hiddenname);
	}
	
	// Accesses the value of the submit field, i.e. which submit button was pressed.
	
	public function submitvalue() {
		return strip_tags(Request::post('submit'));
	}
	
	// Returns true if the form has been submitted, false otherwise.
	
	public function submitted() {
		return $this->submitted;
	}
	
	// Changes the action attribute of this form.
	
	public function setaction($url) {
		$this->action = $url;
	}
	
	
	/* * *
	 VIEWS
	* * */
	
	public function getview() {
		if ($this->render) {
			$Views = array();
			$Views[] = $this->getheaderview();
			$Views[] = $this->getfieldview();
			$Views[] = $this->getbuttonview();
			$Views[] = $this->getfooterview();
			return $Views;
		} else return new View('empty');
	}
	
	public function getbuttonview() {
		return new View('forms/buttons', array('Buttons' => $this->Buttons));
	}
	
	public function getfieldview() {
		return new View('forms/fields', array('Fields' => $this->Fields));
	}
	
	public function getheaderview() {
		return new View('forms/formheader', array('style' => $this->style, 'Hiddens' => $this->Hiddens, 'action' => $this->action));
	}
	
	public function getfooterview() {
		return new View('forms/formfooter', array('ajax' => $this->ajax));
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $Session;
	protected $render = true, $submitted, $style, $ajax = false, $action = '';
	
	// Returns true if all fields valid, returns false otherwise.
	
	protected function validate() {
		$errors = 0;
		foreach ($this->Fields as $Field) {
			if (!$Field->validate()) $errors++;
		}
		if ($errors === 0) {
			return true;
		} else {
			// Clean uploaded files if any.
			if (isset($_FILES['uploaded'])) {
				foreach ($_FILES['uploaded'] as $file) {
					if (file_exists($file)) {
						unlink($file);
					}
				}
			}
			return false;
		}
	}
	
}

?>