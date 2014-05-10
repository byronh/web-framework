<?php

function match($input, $matchvalue, $errormessage) {
	if ($input != $matchvalue)
		return trim($errormessage);
	return false;
}

?>