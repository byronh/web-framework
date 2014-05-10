<?php

function filenameexists($input, $directory, $extension = NULL) {
	$input = strtolower($input);
	if ($extension) {
		if (!file_exists(ROOT.DS.$directory.DS.$input.'.'.$extension)) {
			return 'File not found.';
		}
	} else {
		if (!file_exists(ROOT.DS.$directory.DS.$input)) {
			return 'File not found.';
		}
	}
	
	return false;
}

?>