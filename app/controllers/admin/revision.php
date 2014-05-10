<?php

function reloadversions(&$Versions, $path) {
	$Versions->purge()->where('Version_path=?', $path)->sortdesc('Version_number')
	->load('Version_path,Version_versionpath,Version_number,Version_created,Version_modified,Version_comment,Version_live,User_id,User_name');
}

$this->loadheader('Admin');
$path = Request::get('path');
$back = Request::get('back');

if ($path && $back) {
	$path = base64_decode($path);
	$back = base64_decode($back);
}

if ($this->rank() >= 6 && $path && $back && file_exists($path)) {
	$File = new File($path);
	if ($File->ancestor('app')) {
		$Versions = new Set('Version');
		reloadversions($Versions, $File->getpath());
		
		$rows = array();
//		if ($Versions->count() == 0) {
//			$Version = new Version;
//			$Version->Version_live = 1;
//			$Version->create($path, $File->source(), $this->userid(), '');
//			reloadversions($Versions, $File->getpath());
//		}
		
		$Form = new Form;
		$Form->add(new TextBox('Version Info', 'maxlength[30]'));
		if ($Form->handle()) {
			$version = new File($Form->submitvalue());
			$File->write($version->source());
			$Versions->purge()->where('Version_live=1')->where('Version_path=?', $File->getpath())->update(array('Version_live' => 0));
			$Versions->where('Version_versionpath=?', $version->getpath())->update(array('Version_live' => 1));
			reloadversions($Versions, $File->getpath());
		}
		
		foreach ($Versions as $Version) {
			$path = $Version->Version_versionpath;
			$rows[] = array(
				new LinkButton('View', 'page_white_code', 'neutral popup', '/admin/source?path='.base64_encode($path)),
				new LinkButton('Edit', 'page_white_edit', 'neutral popup', '/admin/edit?path='.base64_encode($path)),
				new View('paragraph', array('text' => $Version->Version_number)),
				new View('paragraph', array('text' => Date::short($Version->Version_created))),
				new View('paragraph',array('text' => Date::short($Version->Version_modified))),
				new View('link', array('label' => $Version->User->User_name, 'link' => '/profile/'.$Version->User_id)),
				new View('paragraph', array('text' => htmlentities($Version->Version_comment))),
				$Version->Version_live ?
					new View('invisiblebutton', array('text' => '(Current)')):
					new SubmitButton('Publish', 'page_white_world', 'positive', $path, true)
			);
		}
		
		$this->loadview('title', array('title' => 'Version History for '.str_replace(ROOT.DS.'app', '', $File->getpath())));
		$this->loadview('link', array('label' => 'Go Back', 'link' => htmlentities($back)));
		$address = '/admin/versioncreate?path='.base64_encode($File->getpath()).'&amp;back='.base64_encode(Request::server('REQUEST_URI'));
		$this->loadview(new LinkButton('Create New Version', 'page_white_stack', 'positive', $address));
		$this->loadview('formtable', array(
			'Form' => $Form,
			'headings' => array('Source', 'Edit', 'Version #', 'Created', 'Last Modified', 'Modified By', 'Version Info', 'Publish'),
			'rows' => $rows
		));
	}
}

?>