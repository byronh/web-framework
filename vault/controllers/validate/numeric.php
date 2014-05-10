<?php

function numeric($input) {
	if (!is_numeric($input))
		return 'Must be numeric.';
	return false;
}

?>