<?php

$this->loadheader('Admin');

if ($this->rank() >= 6) {
	$this->loadview('title', array('title' => 'Create Controller'));
	$this->loadview('link', array('link' => '/admin/controllermanage', 'label' => 'Back to Controllers'));
	
	$Form = new AjaxForm();
	$Form->add(new TextBox('Controller Name', 'required|alpha|minlength[3]|maxlength[15]|classnameavailable'));
	$Form->add(new SubmitButton('Create Controller', 'controller_add', 'positive'));
	$Form->add(new CancelButton('/admin/controllermanage'));
	
	if ($Form->handle()) {
		$name = strtolower($Form->input(0));
		$content = "<?php\n\nclass ".$Form->input(0)." extends MMController {\n\t\n}\n\n?>";
		new File(ROOT.DS.'app'.DS.'controllers'.DS.$name.'.php', $content);
		$Folder = new Folder(ROOT.DS.'app'.DS.'controllers'.DS.$name);
		$Folder->createfile('index.php', "<?php\n\n\n\n?>");
		goto('/admin/controllermanage');
	}
	
	$this->loadview($Form);
}

?>