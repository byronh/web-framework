<?php

function emailavailable($input) {
	$Users = new Set('User');
	$result = $Users->where('User_email=?', $input, 's')->get();
	if ($result)
		return 'Email already in use by another account.';
	return false;
}

?>