<?php

$this->loadheader('Validate', 'full', false, true);

if (!empty($this->args)) {
	
	$id = strtok($this->args[0], '~');
	$token = strtok('~');
	
	$User = new User($id);
	$loaded = $User->load('User_validated');
	
	if ($loaded && $User->User_validated) {
		if ($this->Session->loggedin() === true) {
			goto('/');	
		} else {
			$this->loadview('accounts/validate/failure');
		}
	} elseif ($this->Session->authenticatetoken($id, $token, true)) {
		if ($this->Session->loggedin()) {
			goto('/');
		} else {
			$this->loadview('accounts/login/failure');
		}
	} else {
		$this->loadview('accounts/validate/default');
	}
} else {
	$this->loadview('accounts/validate/default');
}

?>