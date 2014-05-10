<?php

class CancelButton extends LinkButton {
	
	/* * * *
	 PUBLIC
	* * * */
	
	// Constructor parameters:
	//	$link - url to go to when cancel button is pressed.
	
	public function __construct($linkurl = '/') {
		parent::__construct('Cancel', 'cancel', 'negative', $linkurl);
	}
	
	public function getformview() {
		return new View('forms/linkbutton', $this->properties);
	}
	
}

?>