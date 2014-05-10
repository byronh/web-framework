<?php

$this->loadheader('Register', 'split', true, true);

$Form = new AjaxForm();
$Form->add(new TextBox('Username', 'required|alphanumeric|minlength[3]|maxlength[20]|usernameavailable'));
$Form->add(new PasswordBox('Password', 'required|minlength[5]|maxlength[75]'));
$Form->add(new PasswordBox('Confirm Password', 'required|confirm[field1,Passwords do not match.]'));
$Form->add(new TextBox('Email', 'required|emailvalid|emailavailable'));
$Form->add(new SubmitButton('Register!', 'user_add', 'positive'));
$Form->add(new CancelButton());

if ($Form->handle()) {	
	$User = new User;
	$Hasher = new PasswordHash;
	
	$User->User_name = $Form->input('Username');
	$User->User_joined = time();
	$User->User_password = $Hasher->HashPassword($Form->input('Password'));
	$User->User_email = $Form->input('Email');
	$User->Rank_id = 2;
	$User->save();
	
	$data = array('username' => $User->User_name, 'validatekey' => $User->User_id.'~'.$User->addtoken());
	$altbody = new View('accounts/register/emailalt', $data);
	$htmlbody = new View('accounts/register/emailhtml', $data);
	
	$this->loadhelper('mail');
	if (sendmail($User->User_email, 'Account Confirmation', $altbody->output(), $htmlbody->output())) {
		$this->loadview('accounts/register/success');
	} else {
		$User->delete();
		$this->loadview('accounts/register/failure');
	}
} else {
	$this->loadview('accounts/register/default');
}

$this->loadview($Form)->loadview('accounts/register/sidebar');

?>