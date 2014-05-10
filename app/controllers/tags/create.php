<?php

$this->loadheader('Create Tags');

if ($this->rank() >= 4) {
	$this->loadview('admin/title', array('title' => 'Create Tags'));
	
	$Tags = new Set('Tag');
	$Tags->sortasc('Tag_name')->load('Tag_name');
	$this->loadview('tags/edit', array('Tags' => $Tags));
	
	$Form = new AjaxForm();
	$Form->add(new TextBox('Tag Name', 'required|minlength[3]|maxlength[40]|tagnameavailable'));
	$Form->add(new SubmitButton('Create Tag', 'tag_blue_add', 'positive'));
	$Form->add(new CancelButton('/admin'));
	
	if ($Form->handle()) {
		$Tag = new Tag();
		$Tag->Tag_name = ucwords($Form->input('Tag Name'));
		$Tag->save();
		goto('/tags/create');
	}
	
	$this->loadview($Form);
}

?>