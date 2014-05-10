<?php

$this->loadheader('Delete Article');

if ($this->rank() >= 2) {
	if (isset($this->args[0])) {
		$id = $this->args[0];
		$Story = new Story($id);
		if ($Story->load('User_id,Story_type,Story_title,Story_image') && $Story->Story_type <= 0 && $this->userid() == $Story->User_id) {
			
			$Form = new Form();
			$Form->add(new SubmitButton('Delete', 'cross', 'negative'));
			$Form->add(new CancelButton('/contribute'));
			
			if ($Form->handle()) {
				
				// Delete related images
				if (!empty($Story->Story_image) && file_exists($path = ROOT.DS.'public'.DS.'upload'.DS.'preview'.DS.$Story->Story_image)) {
					unlink($path);	
				}
				if ($Feature = make('Set', 'Feature')->where('Story_id=?', $Story->Story_id, 'i')->get('Feature_image,Feature_thumb')) {
					if (!empty($Feature->Feature_image) && file_exists($path = ROOT.DS.'public'.DS.'upload'.DS.'feature'.DS.'full'.DS.$Feature->Feature_image)) {
						unlink($path);
					}
					if (!empty($Feature->Feature_thumb) && file_exists($path = ROOT.DS.'public'.DS.'upload'.DS.'feature'.DS.'thumb'.DS.$Feature->Feature_thumb)) {
						unlink($path);
					}
				}
				
				$Story->delete();
				
				goto('/contribute');	
			}
			
			$this->loadview('content/delete', array('title' => $Story->Story_title));
			$this->loadview($Form);
		} else {
			$this->error('itemnotfound');
		}
	} else {
		$this->error('itemnotfound');
	}
} else {
	$this->error('loginrequired');
}

?>