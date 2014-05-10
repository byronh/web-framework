<?php

class DeleteButton extends SubmitButton {
	
	/* * * *
	 PUBLIC
	* * * */
	
	public function __construct($valueoverride = NULL) {
		parent::__construct('Delete', 'cross', 'negative', $valueoverride, true);
	}
	
}

?>