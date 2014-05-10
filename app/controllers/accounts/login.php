<?php

$this->loadheader('Login', 'full', true, true);

$Form = new Form();
$Form->add(new TextBox('Email Address', 'required|emailexists|emailvalidated'));
$Form->add(new PasswordBox('Password', 'required|correctpassword[field0]'));
$Form->add(new CheckBox('Remember me'));
$Form->add(new Hidden('redirurl', Request::get('redirurl') ? Request::get('redirurl') : $Form->hidden('redirurl')));
$Form->add(new SubmitButton('Log in', 'key_go', 'positive'));
$Form->add(new CancelButton());

if ($Form->handle()) {
	$this->Session->authenticatelogin($Form->input('Email Address'), $Form->input('Password'), $Form->input('Remember me'), $Form->hidden('redirurl'));
	if ($this->Session->loggedin()) {
		($Form->hidden('redirurl')) ? goto($Form->hidden('redirurl')) : goto('/');
	} else {
		$this->loadview('accounts/login/failure');
	}
} else {
	$this->loadview('accounts/login/default');
}

$this->loadview($Form);

?>