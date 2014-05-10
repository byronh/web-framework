<?php

function alpha($input) {
	$temp = preg_replace("([a-zA-Z]+)", '', $input);
	if (!empty($temp))
		return 'Must contain only letters.';
	return false;
}

?>