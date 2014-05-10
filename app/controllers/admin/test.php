<?php

$this->loadheader();

if ($this->rank() >= 7) {
	
	$this->loadview('admin/title', array('title' => 'Testing Page'));
	
	$settings = array(
		'required' => true
	);
	
	$Form = new AjaxForm('left');
	$Form->add(new ImageUploader('Image', $settings));
	$Form->add(new SubmitButton());
	
	if ($Form->handle()) {
		print_r($_FILES);
	}
	
	$this->loadview($Form);
		
}

?>