<?php

$this->loadheader('Admin');
$Folder = new Folder(ROOT.DS.'app'.DS.'controllers');

if ($this->rank() >= 6 && isset($this->args[0]) && $Subfolder = $Folder->getfolder($this->args[0])) {
	
	$controller = ucfirst($this->args[0]);
	$this->loadview('title', array('title' => $controller))
	     ->loadview('link', array('link' => '/admin/controllermanage', 'label' => 'Back to Controllers'))
	     ->loadview(new LinkButton('Create New Action', 'page_white_add', 'positive', '/admin/actioncreate/'.$this->args[0]))
		 ->loadview('space');
	
	$rows = array();
	$actions = array_merge(
		$Subfolder->getfilesbyextension('php'),
		make('Folder', ROOT.DS.'library'.DS.'controllers'.DS.$this->args[0])->getfilesbyextension('php')
	);
	foreach ($actions as $action) {
		$path = $action->getpath();
		$editable = ($action->ancestor('app'));
		$Version = make('Set', 'Version')->where('Version_path=?', $path)->sortdesc('Version_number')
										 ->get('Version_number,Version_modified,User_id,User_name');
		$rows[] = array(
			new LinkButton('Source', 'page_white_code', 'neutral popup', '/admin/source?path='.base64_encode($path)),
			$editable ?
				new LinkButton('Revision', 'page_white_stack', 'neutral', '/admin/revision?path='.base64_encode($path).'&amp;back='.base64_encode(Request::server('REQUEST_URI'))):
				new View('empty'),
			new View('paragraph', array('text' => $action->getname())),
			new View('link', array('link' => SITE_ADDRESS.DS.$this->args[0].DS.$action->getname())),
			new View('paragraph', array('text' => $Version ? $Version->Version_number : '')),
			new View('paragraph', array('text' => $Version ? Date::ago($Version->Version_modified) : '')),
			$Version ?
				new View('link', array('link' => '/profile/'.$Version->User->User_id,'label' => $Version->User->User_name)):
				new View('empty'),
		);
	}
	
	$this->loadview('table', array(
		'headings' => array('Source', 'Edit', 'Action', 'Address', 'Version #', 'Last Modified', 'Modified By'),
		'rows' => $rows
	));
}

?>