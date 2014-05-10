<?php

$this->loadheader('Admin');

if (!$Ranks = Cache::get('admin_index_ranks', 1440)) {
	$Funcs = new Set('AdminFunction');
	$Funcs->select('Rank_id,AdminFunction_type,AdminFunction_label,AdminFunction_link,AdminFunction_image')->sortasc('AdminFunction_label');
	$Ranks = new Set('Rank');
	$Ranks->addchild($Funcs)->where('Rank_id>=3')->sortdesc('Rank_id')->load('Rank_id,Rank_name,Rank_color');
	Cache::set('admin_index_ranks', $Ranks);
}

$this->loadview('admin/index', array('Ranks' => $Ranks, 'userrank' => $this->rank()));

$Users = new Set('User');
$Users->where('Rank_id>=5')->sortasc('User_name')->load('User_name');

if ($this->rank() >= 5) {
	$Form = new AjaxForm();
	$Form->add(new TextBox('Item', 'required|minlength[5]|maxlength[255]'));
	$Form->add(new DropdownMenu('User', 'required', $Users->arraymap('User_name')));
	$Form->add(new DropdownMenu('Priority', 'required', array('Low', 'Medium', 'High')));
	$Form->add(new SubmitButton('Add Item', 'note_add', 'positive'));
	
	if ($Form->handle()) {
		$Todo = new Todo;
		$Filter = new Filter;
		$Todo->Todo_item = $Filter->purify($Form->input('Item'));
		$Todo->User_id = $Form->input('User');
		$Todo->Todo_priority = $Form->input('Priority');
		$Todo->Todo_time = time();
		$Todo->save();
		goto('/admin');
	}
}

$Todos = new Set('Todo');
$Todos->sortasc('User_name')->sortdesc('Todo_priority')->sortdesc('Todo_time')->load('Todo_item,User_name');

$this->loadview('admin/todolist', array('Todos' => $Todos, 'rank' => $this->rank()));

if ($this->rank() >= 5) {
	$this->loadview($Form);
}

?>