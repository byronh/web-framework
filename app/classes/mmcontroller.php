<?php

abstract class MMController extends Controller {
	
	// Constructor - loads helpers and begins Session.
	
	public function __construct($action, $args) {
		parent::__construct($action, $args);
		$this->loadhelper('html');
		$this->Session = Factory::set('Session', new MMSession);
	}
	
	// Destructor - renders footer if header has been loaded.
	
	public function __destruct() {
		parent::__destruct();
		if (isset($this->headerloaded)) {
			$View = new View('footer');
			$View->render();
		}
	}
	
	
	/* * * * *
	 FUNCTIONS
	* * * * */
	
	// Returns the rank of the current User.
	
	protected function rank() {
		if ($this->Session->loggedin()) return $this->Session->getUser()->Rank_id;
		return 0;
	}
	
	// Returns the user id of the current User.
	
	protected function userid() {
		if ($this->Session->loggedin()) return $this->Session->getUser()->User_id;
		return NULL;
	}
	
	// Shows an error view.
	
	protected function error($type, array $data = array()) {
		if (!isset($this->headerloaded)) {
			$this->loadheader();
		}
		$this->loadview('errors/'.$type, $data);	
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $Session;
	protected $headerloaded;
	
	// Loads a header with the given layout.
	
	protected function loadheader($title = NULL, $layout = 'full', $focusinput = false, $hidelogin = false) {
		$this->Views[] = new View('header'.$layout, array('headerinfo' => array(
			'title' => $title,
			'form' => $focusinput,
			'hidelogin' => $hidelogin,
			'User' => $this->Session->getUser()
		)));
		$this->headerloaded = true;
		return $this;
	}
	
}

?>