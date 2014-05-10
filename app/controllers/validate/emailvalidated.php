<?php

function emailvalidated($input) {
	$Users = new Set('User');
	$User = $Users->where('User_email=?', $input, 's')->get('User_validated');
	if (!$User || !$User->User_validated)
		return 'Account not validated. Check your email for confirmation.';
	return false;
}

?>