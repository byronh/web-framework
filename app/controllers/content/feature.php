<?php

$this->loadheader('Add Feature');

if ($this->rank() >= 5 && isset($this->args[0])) {
	$Story = new Story($this->args[0]);
	if ($Story->load('Story_type,Story_title,(SELECT Feature_active FROM Feature WHERE Story_id=Story.Story_id) AS Feature_active') && $Story->Feature->Feature_active!=1 && $Story->Story_type==2) {
		
		$Features = new Set('Feature');
		$Feature = $Features->where('Story_id=?', $Story->Story_id, 'i')->get('Feature_image,Feature_thumb');
		
		$Form = new Form('left');
		$Form->add(new ImageUploader('Image', array(
			'required' => empty($Feature->Feature_image),
			'maxsize' => 100,
			'minwidth' => 704,
			'minheight' => 300,
			'maxwidth' => 704,
			'maxheight' => 300,
			'uploadfolder' => new Folder(ROOT.DS.'public'.DS.'upload'.DS.'feature'.DS.'full'),
			'usecurrent' => true,
			'currentname' => $Feature->Feature_image
		)));
		$Form->add(new ImageUploader('Thumbnail', array(
			'required' => empty($Feature->Feature_thumb),
			'maxsize' => 100,
			'minwidth' => 210,
			'minheight' => 100,
			'maxwidth' => 210,
			'maxheight' => 100,
			'uploadfolder' => new Folder(ROOT.DS.'public'.DS.'upload'.DS.'feature'.DS.'thumb'),
			'usecurrent' => true,
			'currentname' => $Feature->Feature_thumb
		)));
		$Form->add(new SubmitButton('Add Feature', 'heart_add', 'positive'));
		$Form->add(new CancelButton('/content/manage'));
		
		if ($Form->handle()) {
			$Feature = new Feature($Feature ? $Feature->Feature_id : NULL);
			$Feature->Story_id = $Story->Story_id;
			$image = $Form->input(0);
			if (!empty($image)) {
				$Feature->Feature_image = $image['filename'];
			}
			$thumb = $Form->input(1);
			if (!empty($thumb)) {
				$Feature->Feature_thumb = $thumb['filename'];
			}
			$Feature->Feature_active = true;
			$Feature->Feature_added = time();
			$Feature->save();
			
			// Mark old features inactive
			$Features = new Set('Feature');
			$count = $Features->get('COUNT(*) AS Feature_num')->Feature_num;
			$limit = ($count > 3) ? $count - 3 : 0;
			$Features->sortasc('Feature_added')->limit($limit)->update(array(
				'Feature_active' => 0
			));
			
			goto('/content/manage');
		}
		
		$this->loadview('content/feature', array('title' => $Story->Story_title));
		$this->loadview($Form);
	}
}

?>