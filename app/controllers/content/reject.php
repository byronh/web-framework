<?php

$this->loadheader('Reject Article');

if ($this->rank() >= 5 && isset($this->args[0])) {
	$Story = new Story($this->args[0]);
	if ($Story->load('Story_type,Story_title') && $Story->Story_type == 1) {
		
		$Form = new Form();
		$Form->add(new SubmitButton('Reject', 'delete', 'negative'));
		$Form->add(new CancelButton('/content/review'));
		
		if ($Form->handle()) {
			$Story->Story_type = -1;
			$Story->save();
			goto('/content/review');	
		}
		
		$this->loadview('content/reject', array('title' => $Story->Story_title));
		$this->loadview($Form);
	}
}

?>