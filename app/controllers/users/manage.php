<?php

$this->loadheader('Admin');
if ($this->rank() >= 5) {
	$this->loadview('admin/title', array('title' => 'Manage Users'));
	$Form = new Form();
	
	if ($Form->handle()) {
		$operation = strtok($Form->submitvalue(), '_');
		$userid = strtok('_');
		$newrank = ($operation == 'p') ? strtok('_') + 1 : strtok('_') - 1;
		$User = new User($userid);
		$User->Rank_id = $newrank;
		$User->save();
	}
	
	$Users = new Set('User');
	$Users->where('User_validated=1')->paginate(10)->sortdesc('Rank_id')->sortasc('User_name')->load('User_name,User_joined,Rank_id,Rank_name,Rank_color');
	$rows = array();
	foreach ($Users as $User) {
		$promote = ($User->Rank_id + 1 < $this->rank()) ?
			new SubmitButton('', 'move_up', 'positive', 'p_'.$User->User_id.'_'.$User->Rank_id):
			new View('invisiblebutton');
		$demote = ($User->Rank_id < $this->rank() && $User->Rank_id > 1) ?
			new SubmitButton('', 'move_down', 'negative', 'd_'.$User->User_id.'_'.$User->Rank_id):
			new View('invisiblebutton');
		$rows[] = array(
			$promote,
			$demote,
			new View('link', array('link' => '/profile/'.$User->User_id, 'label' => $User->User_name)),
			new View('paragraph', array('text' => $User->Rank->Rank_name, 'color' => $User->Rank->Rank_color)),
			new View('paragraph', array('text' => Date::long($User->User_joined))),
		);
		
	}
	
	$this->loadview('basicpaginator', array('Paginator' => $Users->Paginator));
	$this->loadview('formtable', array(
		'headings' => array('Promote', 'Demote', 'Name', 'Rank', 'Joined'),
		'rows' => $rows,
		'Form' => $Form
	));
	$this->loadview('basicpaginator', array('Paginator' => $Users->Paginator));
}

?>