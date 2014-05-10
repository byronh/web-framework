<?php

$this->loadheader('Rename Tag');

if ($this->rank() >= 5) {
	if (isset($this->args[0])) {
		$id = $this->args[0];
		$Tag = new Tag($id);
		if ($Tag->load('Tag_name')) {
			
			$Form = new AjaxForm();
			$Form->add(new TextBox('Tag Name', 'required|minlength[3]|maxlength[40]|tagnameavailable'), $Tag->Tag_name);
			$Form->add(new SubmitButton('Rename', 'tag_blue_edit', 'positive'));
			$Form->add(new CancelButton('/tags/manage'));
			
			if ($Form->handle()) {
				$Tag->Tag_name = ucwords($Form->input(0));
				$Tag->save();
				goto('/tags/manage');	
			}
			
			$this->loadview('title', array('title' => 'Rename Tag'));
			$this->loadview('link', array('link' => '/tags/manage', 'label' => 'Back to Manage Tags'));
			$this->loadview($Form);
		} else {
			$this->error('itemnotfound');
		}
	}
}

?>