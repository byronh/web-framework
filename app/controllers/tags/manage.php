<?php

$this->loadheader('Manage Tags');

if ($this->rank() >= 5) {
	$this->loadview('admin/title', array('title' => 'Manage Tags'));
	$this->loadview(make('LinkButton', 'Create New Tag', 'tag_blue_add', 'positive', '/tags/create'))->loadview('space');
	
	$Tags = new Set('Tag');
	$Tags->sortasc('Tag_name')->paginate(20)->load('Tag_name, (SELECT COUNT(*) FROM Link JOIN Story ON Story.Story_id = Link.Story_id WHERE Tag_id = Tag.Tag_id AND Story.Story_type >= 1) AS Tag_numarticles');
	
	$rows = array();
	foreach ($Tags as $id => $Tag) {
		$rows[] = array(
			new LinkButton('Rename', 'tag_blue_edit', 'neutral', '/tags/rename/'.$id),
			new View('paragraph', array('text' => $Tag->Tag_name)),
			new View('link', array('link' => '/articles/?tag='.$Tag->Tag_id,'label' => $Tag->Tag_numarticles)),
			new LinkButton('Delete', 'cross', 'negative', '/tags/delete/'.$id)
		);
	}
	
	$this->loadview('basicpaginator', array('Paginator' => $Tags->Paginator));
	$this->loadview('table', array(
		'headings' => array('Edit', 'Tag Name', 'Articles', 'Delete'),
		'rows' => $rows
	));
	$this->loadview('basicpaginator', array('Paginator' => $Tags->Paginator));
}