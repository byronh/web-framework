<?php

$this->loadheader('Submit Article');

if ($this->rank() >= 2) {
	if (isset($this->args[0])) {
		$id = $this->args[0];
		$Story = new Story($id);
		if ($Story->load('User_id,Story_type,Story_title') && $Story->Story_type <= 0 && $this->userid() == $Story->User_id) {
			
			$ref = Request::get('ref');
			
			$Form = new Form();
			$Form->add(new SubmitButton('Submit', 'world_go', 'positive'));
			$Form->add(new LinkButton('Preview', 'page_white_go', 'neutral', '/content/preview/'.$id.'?ref=/content/submit/'.$id.($ref ? '?ref='.$ref : '')));
			$Form->add(new CancelButton($ref ? $ref : '/contribute'));
			
			if ($Form->handle()) {
				$Story->Story_type = 1;
				$Story->save();
				goto('/contribute');	
			}
			
			$this->loadview('content/submit', array('title' => $Story->Story_title));
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