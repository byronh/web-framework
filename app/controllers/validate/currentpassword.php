<?php

// DO NOT USE IN AJAX FORMS.
function currentpassword($input, $userid) {
	$Users = new Set('User');
	$Hasher = new PasswordHash;
	$User = $Users->where('User_id=?', $userid, 'i')->get('User_password');
	if (empty($User))
		return;
	if (!$Hasher->checkpassword($input, $User->User_password))
		return 'Incorrect password.';
	return false;
}

?>