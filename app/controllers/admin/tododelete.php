<?php

$id = Request::get('id');
if ($this->rank() >= 5 && $id) {
	$Todo = new Todo($id);
	$Todo->delete();
	goto('/admin');
}

?>