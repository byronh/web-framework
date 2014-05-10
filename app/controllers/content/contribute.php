<?php

$this->loadheader('Contribute');	

if ($this->rank() >= 2) {	
	$this->loadview('content/contribute');
	$this->loadview('title', array('title' => 'My Articles'));
	$this->loadview(make('LinkButton', 'Create New Article', 'page_white_add', 'positive', '/content/create'));
	
	$Stories = new Set('Story');
	$Stories->where('Story.User_id='.$this->userid())->paginate(10)->sortasc('Story_type')->sortdesc('Story_edited')->load('Story_title,Story_type,Story_edited');
	
	$statuses = array(
		-1 => 'Rejected',
		0 => 'In Progress',
		1 => 'Submitted',
		2 => 'Published'
	);
	$statuscolors = array(
		-1 => '#D12F19',
		0 => '#2F49EA',
		1 => '#CD722F',
		2 => '#529214'
	);
	
	if ($Stories->Paginator->totalpages == 1) {
		$Stories->Paginator->hideresults();
	}
	
	$rows = array();
	foreach ($Stories as $id => $Story) {
		$edit = ($Story->Story_type <= 0) ?
			new LinkButton('Edit', 'page_white_edit', 'neutral', '/content/edit/'.$id):
			new View('empty');
		if ($Story->Story_type == 2) {
			$preview = new LinkButton('View', 'page_white_go', 'neutral', '/article/'.$id);
		} else {
			$preview = new LinkButton('Preview', 'page_white_go', 'neutral', '/content/preview/'.$id);
		}
		$submit = ($Story->Story_type <= 0) ?
			new LinkButton('Submit', 'world_go', 'positive', '/content/submit/'.$id):
			new View('empty');
		$delete = ($Story->Story_type <= 0) ?
			new LinkButton('Delete', 'cross', 'negative', '/content/delete/'.$id):
			new View('empty');
		$rows[] = array(
			$edit,
			$preview,
			new View('paragraph', array('text' => $Story->Story_title)),
			new View('invisiblebutton', array('text' => $statuses[$Story->Story_type], 'color' => $statuscolors[$Story->Story_type])),
			new View('paragraph', array('text' => Date::day($Story->Story_edited))),
			$submit,
			$delete
		);
	}
	
	if ($Stories->count() > 0) {
		$this->loadview('basicpaginator', array('Paginator' => $Stories->Paginator));
		$this->loadview('table', array(
			'headings' => array('Edit', 'View', 'Title', 'Status', 'Last Modified', 'Submit', 'Delete'),
			'rows' => $rows
		));
		$this->loadview('basicpaginator', array('Paginator' => $Stories->Paginator));
	} else {
		$this->loadview('space');
		$this->loadview('emptytable', array('text' => 'You have not written any articles yet!'))->loadview('space');	
	}
	
} else {
	$this->loadview('content/contribute');
}

?>