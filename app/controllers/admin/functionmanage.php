<?php

$this->loadheader('Admin');

if ($this->rank() >= 6) {	
	$Form = new Form();
	if ($Form->handle()) {
		$Func = new AdminFunction($Form->submitvalue());
		$Func->delete();
		Cache::clear('admin_index_ranks');
	}
	
	$Funcs = new Set('AdminFunction');
	$Funcs->where('Rank.Rank_id<='.$this->rank())->where("AdminFunction_link!='/admin/functionmanage'")->sortdesc('Rank.Rank_id')->sortasc('AdminFunction_label')->load('AdminFunction_type,AdminFunction_label,AdminFunction_link,AdminFunction_image,Rank_id,Rank_name,Rank_color');
	
	foreach ($Funcs as $id => $Func) {
		$linklabel = (strpos($Func->AdminFunction_link, '://') === false) ? $Func->AdminFunction_link : '(external)';
		$rows[] = array(
			new LinkButton('Edit', 'cog_edit', 'neutral', '/admin/functionedit/'.$id),
			new View('paragraph', array('text' => $Func->Rank->Rank_name, 'color' => $Func->Rank->Rank_color)),
			new View('icon', array('icon' => $Func->AdminFunction_image)),
			new View('admin/function', array('Function' => $Func, 'rank' => $this->rank())),
			new View('paragraph', array('text' => $linklabel)),
			new DeleteButton($id)
		);
	}
	
	$this->loadview('admin/title', array('title' => 'Manage Admin Functions'));
	$this->loadview(make('LinkButton', 'Create New Function', 'cog_add', 'positive', '/admin/functioncreate'));
	$this->loadview('formtable', array(
		'Form' => $Form,
		'headings' => array('Edit', 'Rank', 'Image', 'Function', 'Link', 'Delete'),
		'rows' => $rows
	));
}

?>