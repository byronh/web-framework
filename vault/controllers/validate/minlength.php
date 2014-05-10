<?php

function minlength($input, $minlength) {
	if (strlen($input) < $minlength) {
		return 'Must be at least '.$minlength.' characters long.';
	}
	return false;
}

?>