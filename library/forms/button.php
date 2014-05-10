<?php

abstract class Button implements Viewable {
	
	/* * * *
	 PUBLIC
	* * * */
	
	public function __construct($labeltext, $image, $cssclass) {
		$this->properties['labeltext'] = $labeltext;
		$this->properties['imageurl'] = '/icon/'.$image.'.png';
		$this->properties['cssclass'] = ($cssclass) ? 'class="'.$cssclass.'"' : '';
	}
	
	public function getformview() {
		return new View('forms/'.strtolower(get_class($this)), $this->properties);
	}
	
	public function getview() {
		return new View('forms/buttons', array('Buttons' => array($this)));
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $properties = array();
}

?>