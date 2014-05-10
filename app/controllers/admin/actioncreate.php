<?php

$this->loadheader('Admin');
$Folder = new Folder(ROOT.DS.'app'.DS.'controllers');

if ($this->rank() >= 6 && isset($this->args[0]) && $Subfolder = $Folder->getfolder($this->args[0])) {
	
	$this->loadview('title', array('title' => 'Create Controller Action: '.ucfirst($this->args[0])));
	$this->loadview('link', array('link' => '/admin/actionmanage/'.$this->args[0], 'label' => 'Back to '.ucfirst($this->args[0])));
	
	$Form = new AjaxForm();
	$Form->add(new TextBox('Action Name', 'required|alpha|minlength[3]|maxlength[30]|filenameavailable[app/controllers/'.$this->args[0].',php]|filenameavailable[library/controllers/'.$this->args[0].',php]'));
	$Form->add(new SubmitButton('Create Action', 'page_white_add', 'positive'));
	$Form->add(new CancelButton('/admin/actionmanage/'.$this->args[0]));
	
	if ($Form->handle()) {
		$Subfolder->createfile(strtolower($Form->input(0)).'.php', "<?php\n\n\n\n?>");
		goto('/admin/actionmanage/'.$this->args[0]);
	}
	
	$this->loadview($Form);
}

?>