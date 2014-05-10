<?php

function minselected($input, $minselected) {
	if (is_array($input) && count($input) >= $minselected) {
		return false;
	} else {
		return 'Must select at least '.$minselected.' options.';
	}
}

?>