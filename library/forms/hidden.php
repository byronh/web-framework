<?php

class Hidden implements Viewable {
	
	/* * * *
	 PUBLIC
	* * * */
	
	public function __construct($name, $value) {
		$this->name = $name;
		$this->value = $value;
	}
	
	public function getformview() {
		return new View('forms/hidden', array(
			'name' => $this->name,
			'value' => $this->value
		));
	}
	
	public function getview() {
		return $this->getformview();
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $name, $value;
	
}

?>