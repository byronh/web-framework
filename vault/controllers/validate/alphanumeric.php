<?php

function alphanumeric($input) {
	$temp = preg_replace("([a-zA-Z0-9]+)", '', $input);
	if (!empty($temp))
		return 'Must contain only letters or numbers.';
	if (is_numeric($input))
		return 'Must contain at least one letter.';
	return false;
}

?>