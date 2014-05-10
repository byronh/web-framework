<?php

$this->loadheader('Change Password', 'split', true);

if ($this->rank() >= 2) {
	
	$this->Session->newcsrftoken();
	
	$Form = new Form();
	$Form->add(new PasswordBox('Current Password', 'required|currentpassword['.$this->userid().']'));
	$Form->add(new PasswordBox('New Password', 'required|minlength[5]|maxlength[75]'));
	$Form->add(new PasswordBox('Confirm New Password', 'required|confirm[field1,Passwords do not match.]'));
	$Form->add(new SubmitButton('Change Password', 'key', 'positive'));
	$Form->add(new CancelButton('/profile'));
	
	if ($Form->handle()) {
		$User = new User($this->userid());
		$Hasher = new PasswordHash;
		$User->User_password = $Hasher->HashPassword($Form->input('New Password'));
		$User->save();
		$User->removealltokens();
		goto('/account/changepasswordsuccess');
	}
	
	$this->loadview('title', array('title' => 'Change Password'))->loadview('space');
	$this->loadview($Form);
	$this->loadview('users/sidebar');
} else {
	$this->error('loginrequired');	
}

?>