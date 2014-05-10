<?php

function exactselected($input, $exactselected) {
	if (is_array($input) && count($input) == $exactselected) {
		return false;
	} else {
		return 'Must select exactly '.$exactselected.' options.';
	}
}

?>