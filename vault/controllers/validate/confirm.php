<?php

function confirm($input, $otherfield, $errormessage, $otherajax = NULL) {
	if ($otherajax) {
		if ($input != $otherajax)
			return trim($errormessage);
	} else {
		if ($input != $_POST[$otherfield])
			return trim($errormessage);
	}
	return false;
}

?>