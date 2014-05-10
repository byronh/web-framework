<?php

function storynameavailable($input) {
	$Users = new Set('Story');
	$result = $Users->where('Story_title=?', $input, 's')->get();
	if ($result)
		return 'An article already exists with that title.';
	return false;
}

?>