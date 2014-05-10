<?php

$this->loadheader('Edit Comment');

if ($this->rank() >= 2) {
	if (isset($this->args[0])) {
		$id = $this->args[0];
		$Comment = new Comment($id);
		if ($Comment->load('Comment_id,User_id,Story_id,Story_title,Comment_contentraw,Rank_id') && ($this->userid() == $Comment->User->User_id || ($this->rank() > $Comment->Rank->Rank_id && $this->rank() > 2))) {
			
			$minlength = 10;
			$maxlength = 2000;
			
			$back = '/article/'.$Comment->Story->Story_id.'#comment'.$id;
			
			$Form = new AjaxForm();
			$Form->add(new TextArea('', 'required|minlength['.$minlength.']|maxlength['.$maxlength.']', 15, 112), $Comment->Comment_contentraw);
			$Form->add(new SubmitButton('Save Edits', 'pencil', 'positive'));
			$Form->add(new CancelButton($back));
			
			if ($Form->handle()) {
				if ($Form->input(0) != $Comment->Comment_contentraw) {
					$Filter = new Filter('basic');
					$Comment = new Comment($id);
					$Comment->Comment_content = $Filter->purify($Form->input(0));
					$Comment->Comment_contentraw = $Form->input(0);
					$Comment->Comment_editby = $this->Session->getUser()->User_name;
					$Comment->Comment_edittime = time();
					$Comment->save();
				}
				goto($back);
			}
			
			$this->loadview('title', array('title' => 'Edit Comment'));
			$this->loadview('link', array('link' => $back, 'label' => 'Back to \''.$Comment->Story->Story_title.'\''));
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