<?php

$this->loadheader('Unpublish Article');

if ($this->rank() >= 5 && isset($this->args[0])) {
	$Story = new Story($this->args[0]);
	if ($Story->load('Story_type,Story_title') && $Story->Story_type == 2) {
		
		$Form = new Form();
		$Form->add(new SubmitButton('Unpublish', 'world_delete', 'negative'));
		$Form->add(new CancelButton('/content/manage'));
		
		if ($Form->handle()) {
			$Story->Story_type = 1;
			$Story->save();
			
			// Mark feature as inactive if this article is featured.
			$Features = new Set('Feature');
			if ($Features->where('Story_id=?', $Story->Story_id, 'i')->load('Feature_active')) {
				foreach ($Features as $Feature) {
					$Feature->Feature_active = 0;
					$Feature->save();	
				}
			}
			
			goto('/content/manage');	
		}
		
		$this->loadview('content/unpublish', array('title' => $Story->Story_title));
		$this->loadview($Form);
	}
}

?>