<?php

function filenameavailable($input, $directory, $extension = NULL) {
	$input = strtolower($input);
	if ($extension) {
		if (file_exists(ROOT.DS.$directory.DS.$input.'.'.$extension)) {
			return 'Filename already taken.';
		}
	} else {
		if (file_exists(ROOT.DS.$directory.DS.$input)) {
			return 'Filename already taken.';
		}
	}
	
	return false;
}

?>