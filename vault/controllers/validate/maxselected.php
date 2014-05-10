<?php

function maxselected($input, $maxselected) {
	if (is_array($input) && count($input) > $maxselected) {
		return 'Must select fewer than '.$maxselected.' options.';
	}
	return false;
}

?>