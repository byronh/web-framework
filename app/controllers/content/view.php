<?php

if (isset($this->args[0])) {
	$id = $this->args[0];
	$Story = new Story($id);
	if ($Story->load('Story_id,User_id,User_name,Story_title,Story_type,Story_published,Story_content') && $Story->Story_type == 2) {
		
		$this->loadheader($Story->Story_title, 'split');
		
		$Stories = new Set('Story');
		$Stories->where('Story_id IN (SELECT Story_id FROM Link WHERE Tag_id IN (SELECT Tag_id FROM Link WHERE Story_id=?) AND Story_id != ?)', $id, 'i')->sortdesc('Story_published')->load('Story_title');
		
		$BBCode = new BBCode();
		$Story->Story_content = $BBCode->replace($Story->Story_content, $Story->User_id);
		
		$data = array(
			'Story' => $Story,
			'User' => $Story->User,
			'Stories' => $Stories
		);
			
		$minlength = 10;
		$maxlength = 2000;
		
		if ($this->rank() >= 2) {
			$Form = new Form('left');
			$Form->add(new TextArea('', 'required|minlength['.$minlength.']|maxlength['.$maxlength.']', 10, 83));
			$Form->add(new SubmitButton('Submit Comment', 'pencil_go', 'positive'));
			
			if ($Form->handle()) {
				$Filter = new Filter('basic');
				$Comment = new Comment();
				$Comment->Story_id = $Story->Story_id;
				$Comment->User_id = $this->userid();
				$Comment->Comment_posted = time();
				$Comment->Comment_contentraw = $Form->input(0);
				$Comment->Comment_content = $Filter->purify($Form->input(0));
				$id = $Comment->save();
						
				goto('/article/'.$Story->Story_id.'#comment'.$id);
			}
			$data['Form'] = $Form;
		}
		
		$Comments = new Set('Comment');
		$Comments->where('Comment.Story_id=?', $id, 'i')->sortasc('Comment_posted')->load('Comment_content,Comment_posted,User_id,User_name,Rank_id,Comment_editby,Comment_edittime');
		
		$i = 1;
		foreach ($Comments as $Comment) {
			$Comment->Comment_editable = $this->userid() == $Comment->User->User_id || ($this->rank() > $Comment->Rank->Rank_id && $this->rank() > 2);
			$Comment->Comment_number = $i;
			$i++;
		}
		
		$data['Comments'] = $Comments;
		
		$this->loadview('content/story', $data);
	} else {
		$this->error('articlenotfound');
	}
} else {
	$this->error('articlenotfound');
}

?>