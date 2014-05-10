<?php

class Admin extends MMController {
	
	public function execute() {
		if ($this->rank() >= 3) {
			parent::execute();
		} else {
			$this->loadheader();
		}
	}
	
}

?>