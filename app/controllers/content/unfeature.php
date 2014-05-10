<?php

$this->loadheader('Unfeature Article');

if ($this->rank() >= 5 && isset($this->args[0])) {
	$id = $this->args[0];
	$Feature = new Feature($id);
	if ($Feature->load('Feature_active,Story_type,Story_title') && $Feature->Feature_active == 1 && $Feature->Story->Story_type == 2) {
		
		$Form = new Form();
		$Form->add(new SubmitButton('Unfeature', 'heart_delete', 'negative'));
		$Form->add(new CancelButton('/content/manage'));
		
		if ($Form->handle()) {
			$Feature = new Feature($id);
			$Feature->Feature_active = 0;
			$Feature->save();
			
			goto('/content/manage');	
		}
		
		$this->loadview('content/unfeature', array('title' => $Feature->Story->Story_title));
		$this->loadview($Form);
	}
}

?>