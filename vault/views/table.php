<?php

if (isset($Paginator)) $this->loadview($Paginator);
$this->loadview('tableheader', array('headings' => $headings));
foreach ($rows as $cells) {
	$this->loadview('tablerow', array('cells' => $cells));
}
$this->loadview('tablefooter');

?>