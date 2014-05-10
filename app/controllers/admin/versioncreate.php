<?php

$this->loadheader('Admin');
$path = Request::get('path');
$back = Request::get('back');

if ($path && $back) {
	$path = base64_decode($path);
	$back = base64_decode($back);
}

if ($this->rank() >= 6 && $path && $back && file_exists($path)) {
	$back = str_replace(';', '', $back);
	$this->loadview('title', array('title' => 'Create New Version of '.str_replace(ROOT.DS.'app', '', $path)));
	$this->loadview('link', array('link' => $back, 'label' => 'Go Back'));
	
	$Form = new AjaxForm();
	$Form->add(new TextBox('Version Info', 'maxlength[30]'));
	$Form->add(new SubmitButton('Create Version', 'page_white_stack', 'positive'));
	$Form->add(new CancelButton($back));
	
	if ($Form->handle()) {
		$Version = new Version;
		$Version->create($path, file_get_contents($path), $this->userid(), strtolower($Form->input(0)));
		goto($back);
	}
	
	$this->loadview($Form);
}

?>