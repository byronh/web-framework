<?php

$this->loadheader('Admin');

if ($this->rank() >= 6) {
	
	$this->loadview('admin/title', array('title' => 'Manage Views'));
	$this->loadview(new LinkButton('Create New View', 'layout_add', 'positive', '/admin/viewcreate'));
	$this->loadview('space');
	
	$Folder = new Folder(ROOT.DS.'app'.DS.'views');
	$views = array_merge(
		$Folder->getfilesbyextension('php', true),
		make('Folder', ROOT.DS.'vault'.DS.'views')->getfilesbyextension('php', true)
	);
	
	$rows = array();
	foreach ($views as $view) {
		$path = $view->getpath();
		$editable = $view->ancestor('app');
		$name = str_replace(array(ROOT.DS.'app'.DS.'views'.DS, ROOT.DS.'vault'.DS.'views'.DS, '.php'), '', $view->getpath());
		$Version = make('Set', 'Version')->where('Version_path=?', $path)->sortdesc('Version_number')
										 ->get('Version_number,Version_modified,User_id,User_name');
		$rows[] = array(
			new LinkButton('Source', 'page_white_code', 'neutral popup', '/admin/source?path='.base64_encode($path)),
			$editable ?
				new LinkButton('Revision', 'page_white_stack', 'neutral', '/admin/revision?path='.base64_encode($path).'&amp;back='.base64_encode(Request::server('REQUEST_URI'))):
				new View('empty'),
			new View('paragraph', array('text' => $name)),
			new View('paragraph', array('text' => $Version ? $Version->Version_number : '')),
			new View('paragraph', array('text' => $Version ? Date::ago($Version->Version_modified) : '')),
			$Version ?
				new View('link', array('link' => '/profile/'.$Version->User->User_id,'label' => $Version->User->User_name)):
				new View('empty'),
		);
	}
	
	$this->loadview('table', array(
		'headings' => array('Source', 'Edit', 'View', 'Version #', 'Last Modified', 'Modified By'),
		'rows' => $rows
	));
}

?>