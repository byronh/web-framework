<?php

$this->loadheader('Admin');

if ($this->rank() >= 6) {
	
	$this->loadview('admin/title', array('title' => 'Manage Models'));
	$this->loadview(new LinkButton('Create New Model', 'brick_add', 'positive', '/admin/modelcreate'));
	$this->loadview('space');
	
	$Folder = new Folder(ROOT.DS.'app'.DS.'models');
	$models = array_merge(
		$Folder->getfilesbyextension('php'),
		make('Folder', ROOT.DS.'vault'.DS.'models')->getfilesbyextension('php')
	);
	
	$rows = array();
	foreach ($models as $model) {
		$path = $model->getpath();
		$editable = $model->ancestor('app') && $model != 'version.php';
		$Version = make('Set', 'Version')->where('Version_path=?', $path)->sortdesc('Version_number')
										 ->get('Version_number,Version_modified,User_id,User_name');
		$rows[] = array(
			new LinkButton('Source', 'page_white_code', 'neutral popup', '/admin/source?path='.base64_encode($path)),
			$editable ?
				new LinkButton('Revision', 'page_white_stack', 'neutral', '/admin/revision?path='.base64_encode($path).'&amp;back='.base64_encode(Request::server('REQUEST_URI'))):
				new View('empty'),
			new View('paragraph', array('text' => $model->getname())),
			new View('paragraph', array('text' => $Version ? $Version->Version_number : '')),
			new View('paragraph', array('text' => $Version ? Date::ago($Version->Version_modified) : '')),
			$Version ?
				new View('link', array('link' => '/profile/'.$Version->User->User_id,'label' => $Version->User->User_name)):
				new View('empty'),
		);
	}
	
	$this->loadview('table', array(
		'headings' => array('Source', 'Edit', 'Model', 'Version #', 'Last Modified', 'Modified By'),
		'rows' => $rows
	));
}

?>