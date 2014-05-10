<?php

$this->loadheader('Delete Tag');

if ($this->rank() >= 5) {
	if (isset($this->args[0])) {
		$id = $this->args[0];
		$Tag = new Tag($id);
		if ($Tag->load('Tag_name, (SELECT COUNT(*) FROM Link WHERE Tag_id = Tag.Tag_id) AS Tag_numarticles')) {
			
			$Form = new Form();
			$Form->add(new SubmitButton('Delete', 'cross', 'negative'));
			$Form->add(new CancelButton('/tags/manage'));
			
			if ($Form->handle()) {
				$Tag->delete();
				goto('/tags/manage');	
			}
			
			$this->loadview('tags/delete', array('Tag' => $Tag));
			$this->loadview($Form);
		} else {
			$this->error('itemnotfound');
		}
	}
}

?>