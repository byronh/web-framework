<?php

class SubmitButton extends Button {
	
	/* * * *
	 PUBLIC
	* * * */
	
	// Constructor parameters:
	//	$label - the text and value for this button.
	//	$image - url to the image to be displayed on the button face left of the label.
	//	$cssclass - if this parameter is 'positive', label will have green text, if 'negative', will have red text.
	//	$valueoverride - overrides the POST value of this button. Use only for back-end forms, because this has compatibility issues with IE.
	
	public function __construct($labeltext = 'Submit', $image = 'accept', $cssclass = NULL, $valueoverride = NULL, $confirmed = NULL) {
		parent::__construct($labeltext, $image, $cssclass);
		$this->properties['valueoverride'] = $valueoverride;
		$this->properties['confirmed'] = $confirmed;
	}
	
}

?>