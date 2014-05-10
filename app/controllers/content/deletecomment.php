<?php

$this->loadheader('Delete Comment');

if ($this->rank() >= 2) {
	if (isset($this->args[0])) {
		$id = $this->args[0];
		$Comment = new Comment($id);
		if ($Comment->load('User_id,Rank_id,Story_id') && ($this->userid() == $Comment->User->User_id || ($this->rank() > $Comment->Rank->Rank_id && $this->rank() > 2))) {
			
			$Form = new Form();
			$Form->add(new SubmitButton('Delete', 'cross', 'negative'));
			$Form->add(new CancelButton('/article/'.$Comment->Story->Story_id.'#comment'.$id));
			
			if ($Form->handle()) {
				$Comment->delete();
				goto('/article/'.$Comment->Story->Story_id.'#comments');
			}
			
			$this->loadview('title', array('title' => 'Delete Comment'));
			$this->loadview('paragraph', array('text' => 'Are you sure you wish to delete this comment?'));
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