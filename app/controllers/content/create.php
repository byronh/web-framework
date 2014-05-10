<?php

$this->loadheader('Create Article', 'full', true);

if ($this->rank() >= 2) {
	$this->loadview('title', array('title' => 'Create Article'));
	$this->loadview('link', array('link' => '/contribute', 'label' => 'Back to My Articles'));
	
	$Form = new AjaxForm();
	$Form->add(new TextBox('Title', 'required|minlength[4]|maxlength[40]|storynameavailable'));
	$Form->add(new SubmitButton('Create Article', 'page_white_go', 'positive'));
	$Form->add(new CancelButton('/contribute'));

	
	if ($Form->handle()) {
		$Story = new Story();
		$Story->User_id = $this->userid();
		$Filter = new Filter();
		$Story->Story_title = $Filter->purify(ucwords($Form->input('Title')));
		$Story->Story_type = 0;
		$Story->Story_edited = time();
		$id = $Story->save();
		goto('/contribute');
	}
	
	$this->loadview($Form);
} else {
	$this->error('loginrequired');
}

?>