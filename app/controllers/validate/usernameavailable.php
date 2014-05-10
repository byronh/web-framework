<?php

function usernameavailable($input) {
	$Users = new Set('User');
	$result = $Users->where('User_name=?', $input, 's')->get();
	if ($result)
		return 'Username already taken.';
	return false;
}

?>