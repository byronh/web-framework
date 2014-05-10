<?php

function maxlength($input, $maxlength) {
	if (strlen($input) > $maxlength) {
		return 'Must be shorter than '.$maxlength.' characters long.';
	}
	return false;
}

?>