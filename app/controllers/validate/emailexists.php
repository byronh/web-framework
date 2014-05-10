<?php

function emailexists($input) {
	$Users = new Set('User');
	$result = $Users->where('User_email=?', $input, 's')->get();
	if (!$result)
		return 'Incorrect login information.';
	return false;
}

?>