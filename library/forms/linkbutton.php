<?php

class LinkButton extends Button {
	
	/* * * *
	 PUBLIC
	* * * */
	
	// Constructor parameters:
	//	$label - the text and value for this button.
	//	$image - url to the image to be displayed on the button face left of the label.
	//	$link - if this parameter specified, will act as a link to the given url, otherwise will act as a submit button.
	//	$cssclass - if this parameter is 'positive', label will have green text, if 'negative', will have red text.
	
	public function __construct($labeltext = 'Link', $image = NULL, $cssclass = NULL, $linkurl = NULL) {
		parent::__construct($labeltext, $image, $cssclass);
		$this->properties['linkurl'] = $linkurl;
	}
	
}

?>