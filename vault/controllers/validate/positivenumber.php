<?php

function positivenumber($input) {
	if (!($input > 0))
		return 'Must be a positive number.';
	return false;
}

?>