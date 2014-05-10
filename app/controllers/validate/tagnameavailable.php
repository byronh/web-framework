<?php

function tagnameavailable($input) {
	$Tags = new Set('Tag');
	$result = $Tags->where('Tag_name=?', $input, 's')->get();
	if ($result)
		return 'Tag name already taken.';
	return false;
}

?>