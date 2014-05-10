<?php

function exactlength($input, $length) {
	if (strlen($input) != $length) {
		return 'Must be exactly '.$length.' characters long.';
	}
	return false;
}

?>