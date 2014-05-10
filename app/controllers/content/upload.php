<?php

$this->loadheader();

if ($this->rank() >= 4) {
	
	$this->loadview('title', array('title' => 'Upload Images'));
	$this->loadview('link', array('link' => '/content/images', 'label' => 'Back to Images'));
	$this->loadview('space');
	
	// Creates the user's upload folder if it doesn't already exist
	$Folder = new Folder(ROOT.DS.'public'.DS.'upload'.DS.'user'.DS.$this->userid());
	
	$settings = array(
		'maxsize' => 500,
		'maxwidth' => 1366,
		'maxheight' => 768,
		'uploadfolder' => new Folder(ROOT.DS.'public'.DS.'upload'.DS.'user'.DS.$this->userid().DS.'full'),
		'makethumb' => true,
		'thumbfolder' => new Folder(ROOT.DS.'public'.DS.'upload'.DS.'user'.DS.$this->userid().DS.'thumb'),
		'thumbwidth' => 200,
		'thumbheight' => 200
	);
	
	$Form = new Form('left');
	for ($i=1; $i<=5; $i++) {
		$Form->add(new ImageUploader('Image '.$i, $settings));
	}
	$Form->add(new SubmitButton('Upload Images', 'images', 'positive'));
	$Form->add(new CancelButton('/content/images'));
	
	if ($Form->handle()) {
		goto('/content/images');
	}
	
	$this->loadview('paragraph', array('text' => 'Max file size: '.$settings['maxsize'].'.0 kb'));
	$this->loadview('paragraph', array('text' => 'Max image dimensions: '.$settings['maxwidth'].'x'.$settings['maxheight'].'px'));
	$this->loadview('paragraph', array('text' => 'Allowed file types: gif, jpg, png'))->loadview('space');
	$this->loadview($Form);
}

?>