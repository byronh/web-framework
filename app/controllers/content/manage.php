<?php

$this->loadheader('Manage Live Content');

if ($this->rank() >= 5) {
	$this->loadview('admin/title', array('title' => 'Manage Live Content'));
	
	$Features = new Set('Feature');
	$Features->select('Feature_id')->where('Feature_active=1');
	
	$Stories = new Set('Story');
	$Stories->addchild($Features)->where('Story_type=2')->sortdesc('(SELECT Feature_added FROM Feature WHERE Story_id=Story.Story_id AND Feature_active=1)')->sortdesc('Story_published')->paginate(10)
			->load('Story_title,Story_published,User_id,User_name,(SELECT Feature_added FROM Feature WHERE Story_id=Story.Story_id AND Feature_active=1) AS Story_test');
	
	$rows = array();
	foreach ($Stories as $id => $Story) {
		$feature = empty($Story->Features) ?
			new LinkButton('Feature', 'heart_add', 'positive', '/content/feature/'.$id):
			new LinkButton('Unfeature', 'heart_delete', 'negative', '/content/unfeature/'.current($Story->Features)->Feature_id);
		$rows[] = array(
			new LinkButton('Edit', 'page_white_edit', 'neutral', '/content/edit/'.$id),
			new LinkButton('Retag', 'tag_blue_edit', 'neutral', '/content/retag/'.$id),
			new LinkButton('View', 'page_white_go', 'neutral', '/article/'.$id),
			new View('paragraph', array('text' => $Story->Story_title)),
			new View('link', array('link' => '/profile/'.$Story->User->User_id, 'label' => $Story->User->User_name)),
			new View('paragraph', array('text' => Date::short($Story->Story_published))),
			$feature,
			new LinkButton('Unpublish', 'world_delete', 'negative', '/content/unpublish/'.$id),
		);
	}
	
	$this->loadview('basicpaginator', array('Paginator' => $Stories->Paginator));
	$this->loadview('table', array(
		'headings' => array('Edit', 'Retag', 'View', 'Title', 'Author', 'Date Published', 'Feature', 'Unpublish'),
		'rows' => $rows
	));
	$this->loadview('basicpaginator', array('Paginator' => $Stories->Paginator));
	
}

?>