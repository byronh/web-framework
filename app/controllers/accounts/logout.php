<?php

$Form = new Form();
$Form->add(new SubmitButton('Log out', 'user_go', 'positive'));
$Form->add(new CancelButton());

if ($Form->handle()) {
	$this->Session->logout();
	goto('/');
} else {
	$this->loadheader('Logout', 'full', true, true);
	$this->loadview('accounts/logout/default');
}

$this->loadview($Form);

?>