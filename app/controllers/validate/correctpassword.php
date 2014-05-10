<?php

// DO NOT USE IN AJAX FORMS. $otherfield must be a user email field.
function correctpassword($input, $otherfield) {
	$Users = new Set('User');
	$Hasher = new PasswordHash;
	$User = $Users->where('User_email=?', $_POST[$otherfield], 's')->get('User_password');
	if (empty($User))
		return;
	if (!$Hasher->checkpassword($input, $User->User_password))
		return 'Incorrect login information.';
	return false;
}

?>