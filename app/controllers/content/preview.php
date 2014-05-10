<?php

$this->loadheader('Preview Article', 'split');

if (isset($this->args[0])) {
	$id = $this->args[0];
	$Story = new Story($id);
	if ($Story->load('Story_id,User_id,User_name,Story_title,Story_image,Story_type,Story_edited,Story_tagline,Story_content') && $Story->Story_type < 2 && ($Story->User_id == $this->userid() || $this->rank() >= 4)) {
		
		$BBCode = new BBCode();
		$Story->Story_content = $BBCode->replace($Story->Story_content, $Story->User_id);
		
		if (empty($Story->Story_image)) {
			$Story->Story_image = 'default.jpg';	
		}
		$this->loadview('content/preview', array(
			'Story' => $Story,
			'User' => $Story->User,
			'ref' => Request::get('ref')
		));
	} else {
		$this->error('itemnotfound');
	}
} else {
	$this->error('itemnotfound');
}

?>