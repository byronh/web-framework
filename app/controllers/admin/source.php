<?php

$path = Request::get('path');
if ($path) $path = base64_decode($path);

if ($this->rank() >= 6 && $path && file_exists($path)) {
	$File = new File($path);
	if ($File->ancestor('app') || $File->ancestor('vault')) {
		$this->loadview('popup', array(
			'title' => 'Viewing source: '.$File,
			'Views' => array(
				new LinkButton('Close', 'cross', 'negative close'),
				new View('paragraph', array('text' => $File->source(true)))
			)
		));
	}
}

?>