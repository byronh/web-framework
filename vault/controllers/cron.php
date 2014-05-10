<?php

class Cron extends Controller {
	
	public function execute() {
		if ($_POST['curl_pass'] == CURL_PASS) {
			require(ROOT.DS.'config'.DS.'cron.php');
			parent::execute();
		}
	}
	
}

?>