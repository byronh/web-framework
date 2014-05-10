<?php

class Log extends Model {	
	
	/* * * * * * * * *
	 SECURITY LOGGING
	* * * * * * * * */
	
	// Logs a cross-site request forgery attempt.
	
	public function logcsrf(array $info = array()) {
		$this->addlog('Possible CSRF Attempt', $info);
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	// Inserts a log into the database.
	
	protected function addlog($type, array $info) {
		$this->Log_type = $type;
		$this->Log_time = time();
		$this->Log_ip = Request::server('REMOTE_ADDR');
		$this->Log_url = Request::server('REQUEST_URI');
		$this->Log_referrer = Request::server('HTTP_REFERER');
		$this->Log_info = serialize($info);
		$this->save();
	}
	
}

?>