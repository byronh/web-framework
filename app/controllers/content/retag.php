<?php

$this->loadheader('Retag Article');

if ($this->rank() >= 5 && isset($this->args[0])) {
	$Story = new Story($this->args[0]);
	if ($Story->load('Story_type,Story_title') && $Story->Story_type == 2) {
		
		$Links = new Set('Link');
		$Links->where('Story_id=?', $Story->Story_id, 'i')->sortasc('Tag_name')->load('Tag_id,Tag_name');
		
		$Tags = new Set('Tag');
		$Tags->sortasc('Tag_name')->load('Tag_name');
		
		$Form = new Form('left');
		$Form->add(new MultiCheckBox('', 'required|maxselected[4]', $Tags->arraymap('Tag_name')), $Links->arraymap('Tag_id', 'Tag'));
		$Form->add(new SubmitButton('Save Tags', 'tag_blue_edit', 'positive'));
		$Form->add(new CancelButton('/content/manage'));
		
		if ($Form->handle()) {
			// Delete old tags
			$Links = new Set('Link');
			$Links->where('Story_id=?', $Story->Story_id, 'i')->delete();
			
			// Save new tags
			$tagids = $Form->input(0);
			foreach ($tagids as $tagid) {
				$Link = new Link();
				$Link->Story_id = $Story->Story_id;
				$Link->Tag_id = $tagid;
				$Link->save();
			}
			
			goto('/content/manage');	
		}
		
		$this->loadview('content/retag', array('title' => $Story->Story_title));
		$this->loadview($Form);
	}
}

?>