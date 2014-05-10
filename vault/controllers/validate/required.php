<?php

function required($input) {
	if ((empty($input) && $input != '0') || $input == '_default')
		return 'Required field.';
	else
		return false;
}

?>