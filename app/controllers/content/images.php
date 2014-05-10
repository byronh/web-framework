<?php

$this->loadheader();

if ($this->rank() >= 4) {
	
	$this->loadview('admin/title', array('title' => 'My Images'));
	$this->loadview(make('LinkButton', 'Upload Images', 'image_add', 'positive', '/content/upload'));
	
	$thumbfolder = new Folder(ROOT.DS.'public'.DS.'upload'.DS.'user'.DS.$this->userid().DS.'thumb');
	
	$Files = $thumbfolder->paginatefiles('*', 9);
	$rows = array();
	$row = array();
	$num = 0;
	foreach ($Files as $File) {
		$num++;
		$row[] = new View('admin/image', array(
			'src' => str_replace(ROOT, '', $File->getpath()),
			'link' => str_replace(array(ROOT, 'thumb', 'public'.DS), array('', 'full', ''), $File->getpath()),
			'name' => $File
		));
		if ($num == 3) {
			$num = 0;
			$rows[] = $row;
			$row = array();
		}
	}
	if (!empty($row)) {
		$rows[] = $row;
	}
	
	$this->loadview('script', array('src' => 'imagebank.js'));
	$this->loadview('table', array(
		'headings' => array(),
		'rows' => $rows,
		'Paginator' => $thumbfolder->Paginator
	));
}

?>