<?php

function emailvalid($input) {
	if (!(stristr($input, '@') && stristr($input, '.') && (strlen($input) > 5)))
		return 'Not a valid email address.';
	return false;
}

?>