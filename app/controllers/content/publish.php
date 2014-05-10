<?php

$this->loadheader('Publish Article');

if ($this->rank() >= 5 && isset($this->args[0])) {
	$Story = new Story($this->args[0]);
	if ($Story->load('Story_type,Story_published,Story_title,Story_image') && $Story->Story_type == 1) {
		
		$Links = new Set('Link');
		$Links->where('Story_id=?', $Story->Story_id, 'i')->sortasc('Tag_name')->load('Tag_id,Tag_name');
		
		$Tags = new Set('Tag');
		$Tags->sortasc('Tag_name')->load('Tag_name');
		
		$Form = new Form('left');
		$Form->add(new MultiCheckBox('Select Tags', 'required|maxselected[4]', $Tags->arraymap('Tag_name')), $Links->arraymap('Tag_id', 'Tag'));
		$Form->add(new ImageUploader('Image', array(
				'maxsize' => 50,
				'minwidth' => 160,
				'minheight' => 120,
				'maxwidth' => 160,
				'maxheight' => 120,
				'uploadfolder' => new Folder(ROOT.DS.'public'.DS.'upload'.DS.'preview'),
				'usecurrent' => true,
				'currentname' => $Story->Story_image
			)));
		$Form->add(new SubmitButton('Publish', 'world_go', 'positive'));
		$Form->add(new CancelButton('/content/review'));
		
		if ($Form->handle()) {
			$Story->Story_type = 2;
			$image = $Form->input(1);
			if (!empty($image)) {
				$Story->Story_image = $image['filename'];	
			}
			if (!$Story->Story_published) $Story->Story_published = time();
			$Story->save();
			
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
			
			goto('/content/review');	
		}
		
		$this->loadview('content/publish', array('title' => $Story->Story_title));
		$this->loadview($Form);
	}
}

?>