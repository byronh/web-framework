<?php

$this->loadheader('Edit Article', 'full');

if ($this->rank() >= 2) {
	if (isset($this->args[0])) {
		$id = $this->args[0];
		$Story = new Story($id);
		if ($Story->load('User_id,Story_type,Story_title,Story_tagline,Story_contentraw,Story_image') && (($Story->Story_type <= 0 && $this->userid() == $Story->User_id) || ($Story->Story_type >= 1 && $this->rank() >= 5))) {
			
			$Form = new AjaxForm('left');
			$Form->add(new TextBox('Title', 'required|minlength[8]|maxlength[40]'), $Story->Story_title);
			$Form->add(new TextArea('Tagline', 'required|minlength[20]|maxlength[255]', 5, 100), $Story->Story_tagline);
			$Form->add(new TextArea('Content', 'required', 30, 100), $Story->Story_contentraw);
			$Form->add(new ImageUploader('160x120 Image', array(
				'maxsize' => 50,
				'minwidth' => 160,
				'minheight' => 120,
				'maxwidth' => 160,
				'maxheight' => 120,
				'uploadfolder' => new Folder(ROOT.DS.'public'.DS.'upload'.DS.'preview'),
				'usecurrent' => true,
				'currentname' => $Story->Story_image
			)));
			$Form->add(new SubmitButton('Save', 'page_save', 'neutral', 'save'));
			if ($Story->Story_type <= 1) {
				$Form->add(new SubmitButton('Preview', 'page_white_go', 'neutral', 'preview'));
			} else {
				$Form->add(new CancelButton('/content/manage'));	
			}
			if ($Story->Story_type <= 0) {
				$Form->add(new SubmitButton('Submit', 'world_go', 'positive', 'submit'));
			}
			
			if ($Form->handle()) {
				$Filter = new Filter();
				$Story->Story_title = $Filter->purify(ucwords($Form->input(0)));
				$Story->Story_tagline = $Filter->purify($Form->input(1));
				$Story->Story_content = $Filter->purify($Form->input(2));
				$Story->Story_contentraw = $Form->input(2);
				$image = $Form->input(3);
				if (!empty($image)) {
					$Story->Story_image = $image['filename'];	
				}
				$Story->Story_edited = time();
				$Story->save();
				switch ($Form->submitvalue()) {
					case 'preview':
						goto('/content/preview/'.$id.'?ref=/content/edit/'.$id);	
						break;
					case 'submit':
						goto('/content/submit/'.$id.'?ref=/content/edit/'.$id);	
						break;	
					default:
						goto('/content/edit/'.$id);	
				}
			}
			
			$this->loadview('title', array('title' => 'Editing Article: '.$Story->Story_title));
			
			switch ($Story->Story_type) {
				case 1:
					$this->loadview('link', array('link' => '/content/review', 'label' => 'Back to Review Submissions'));
					break;
				case 2:
					$this->loadview('link', array('link' => '/content/manage', 'label' => 'Back to Manage Live Content'));
					break;
				default:
					$this->loadview('link', array('link' => '/contribute', 'label' => 'Back to My Articles'));
			}
			
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