<?php

$this->loadheader('Admin');

if ($this->rank() >= 6) {
	$Ranks = new Set('Rank');
	$Ranks->where('Rank_id>=3')->where('Rank_id<='.$this->rank())->sortasc('Rank_id')->load('Rank_name');
	$Folder = new Folder(ROOT.DS.'public'.DS.'icon');
	
	$Form = new AjaxForm();
	$Form->add(new DropdownMenu('Type', 'required', array('Normal', 'Confirmed', 'New Tab', 'Popup')));
	$Form->add(new DropdownMenu('Rank', 'required', $Ranks));
	$Form->add(new TextBox('Label', 'required|minlength[5]|maxlength[30]'));
	$Form->add(new TextBox('Link', 'required|minlength[5]|maxlength[80]'));
	foreach ($Folder->getfilesbyextension('png') as $image) {
		$Form->add(new SubmitButton($image->getname(), $image->getname()));
	}
	
	if ($Form->handle()) {
		$Func = new AdminFunction;
		$Func->Rank_id = $Form->input('Rank');
		$Func->AdminFunction_type = $Form->input('Type');
		$Func->AdminFunction_label = $Form->input('Label');
		$Func->AdminFunction_link = $Form->input('Link');
		$Func->AdminFunction_image = $Form->submitvalue();
		$Func->save();
		Cache::clear('admin_index_ranks');
		goto('/admin/functionmanage');
	}
	
	$this->loadview('title', array('title' => 'Create Admin Function'));
	$this->loadview('link', array('link' => '/admin/functionmanage', 'label' => 'Back to Admin Functions'));
	$this->loadview($Form);
}

?>