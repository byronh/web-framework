<?php

$this->loadheader('Change Password', 'split');

if ($this->rank() >= 2) {
	
	$this->Session->newcsrftoken();
	
	$this->loadview('accounts/changepassword/success');
	$this->loadview('users/sidebar');
}

?>