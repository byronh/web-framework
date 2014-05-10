<?php

$this->loadheader('Admin');

if ($this->rank() >= 6) {
	$this->loadhelper('date');
	$this->loadview('admin/title', array('title' => 'Manage Controllers'));
	$this->loadview(new LinkButton('Create New Controller', 'controller_add', 'positive', '/admin/controllercreate'));
	$this->loadview('space');
	
	$Folder = new Folder(ROOT.DS.'app'.DS.'controllers');
	$controllers = array_merge(
		$Folder->getfilesbyextension('php'),
		make('Folder', ROOT.DS.'vault'.DS.'controllers')->getfilesbyextension('php')
	);
	foreach ($controllers as $controller) {
		if ($controller == 'admin.php') continue;
		$path = $controller->getpath();
		$editable = ($controller->ancestor('app'));
		$Version = make('Set', 'Version')->where('Version_path=?', $path)->sortdesc('Version_number')
										 ->get('Version_number,Version_modified,User_id,User_name');
		$rows[] = array(
			new LinkButton('Source', 'page_white_code', 'neutral popup', '/admin/source?path='.base64_encode($path)),
			$editable ?
				new LinkButton('Revision', 'page_white_stack', 'neutral', '/admin/revision?path='.base64_encode($path).'&amp;back='.base64_encode(Request::server('REQUEST_URI'))):
				new View('empty'),
			new LinkButton('Actions', 'page_white_lightning', 'positive', '/admin/actionmanage/'.$controller->getname()),
			new View('paragraph', array('text' => ucfirst($controller->getname()))),
			new View('link', array('link' => SITE_ADDRESS.DS.$controller->getname())),
			new View('paragraph', array('text' => $Version ? $Version->Version_number : '')),
			new View('paragraph', array('text' => $Version ? Date::ago($Version->Version_modified) : '')),
			$Version ?
				new View('link', array('link' => '/profile/'.$Version->User->User_id,'label' => $Version->User->User_name)):
				new View('empty'),
		);
	}
	
	$this->loadview('table', array(
		'headings' => array('Source', 'Revision', 'Manage Actions', 'Controller', 'Address', 'Version #', 'Last Modified', 'Modified By'),
		'rows' => $rows
	));
}

?>