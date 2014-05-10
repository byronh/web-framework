<?php

function classnameavailable($input) {
	if (class_exists($input))
		return 'Another class already exists with that name.';
	return false;
}

?>