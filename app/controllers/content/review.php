<?php

$this->loadheader('Review Submissions');

if ($this->rank() >= 5) {
	$this->loadview('admin/title', array('title' => 'Review Submissions'));
	
	$Stories = new Set('Story');
	$Stories->where('Story_type=1')->paginate(10)->sortdesc('Story_edited')->sortasc('Story_title')->load('Story_title,Story_edited,User_id,User_name');
	
	$rows = array();
	foreach ($Stories as $id => $Story) {
		$rows[] = array(
			new LinkButton('Edit', 'cog_edit', 'neutral', '/content/edit/'.$id),
			new LinkButton('Preview', 'page_white_go', 'neutral', '/content/preview/'.$id.'?ref=/content/review'),
			new View('paragraph', array('text' => $Story->Story_title)),
			new View('link', array('label' => $Story->User->User_name, 'link' => '/profile/'.$Story->User_id)),
			new View('paragraph', array('text' => Date::short($Story->Story_edited))),
			new LinkButton('Publish', 'world_go', 'positive', '/content/publish/'.$id),
			new LinkButton('Reject', 'delete', 'negative', '/content/reject/'.$id)
		);
	}
	
	if ($Stories->count() > 0) {
		$this->loadview('basicpaginator', array('Paginator' => $Stories->Paginator));
		$this->loadview('table', array(
			'headings' => array('Edit', 'Preview', 'Title', 'Author', 'Last Modified', 'Publish', 'Reject'),
			'rows' => $rows
		));
		$this->loadview('basicpaginator', array('Paginator' => $Stories->Paginator));
	} else {
		$this->loadview('space');
		$this->loadview('emptytable', array('text' => 'There are currently no submissions.'))->loadview('space');		
	}
}

?>