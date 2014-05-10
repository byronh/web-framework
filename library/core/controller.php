<?php

abstract class Controller {
	
	/* * * *
	 FACTORY
	* * * */
	
	// Loads a view to be rendered when the controller action is complete. Possible options:
	// - Existing view object
	// - Any object implementing the 'Viewable' interface
	// - Parameters for constructing a new view object (path, associative data array)
	
	protected function loadview($View, array $data = array()) {
		if ($View instanceof View) {
			$this->Views[] = $View;
		} elseif ($View instanceof Viewable) {
			$result = $View->getview();
			if (is_array($result)) {
				foreach ($result as $singleview)
					$this->loadview($singleview);
			} else {
				$this->Views[] = $result;
			}
		} else {
			$this->Views[] = new View($View, $data);
		}
		return $this;
	}
	
	// Loads a function library.
	
	protected function loadhelper($name) {
		$path = ROOT.DS.'library'.DS.'helpers'.DS.$name.'.php';
		if (file_exists($path)) require_once($path);
		return $this;
	}
	
	// Returns the Cache associated with this controller.
	
	protected function cache() {
		if (!isset($this->Cache)) $this->Cache = new Cache();
		return $this->Cache;
	}
	
	
	/* * * *
	 PUBLIC
	* * * */
	
	// Calls a function from the controller's folder.
	
	public function execute() {
		if (file_exists(ROOT.DS.'app'.DS.'controllers'.DS.$this->folder.DS.$this->action.'.php')) {
			require(ROOT.DS.'app'.DS.'controllers'.DS.$this->folder.DS.$this->action.'.php');
		} elseif (file_exists(ROOT.DS.'vault'.DS.'controllers'.DS.$this->folder.DS.$this->action.'.php')) {
			require(ROOT.DS.'vault'.DS.'controllers'.DS.$this->folder.DS.$this->action.'.php');
		} elseif (file_exists(ROOT.DS.'app'.DS.'controllers'.DS.$this->folder.DS.'index.php')) {
			require(ROOT.DS.'app'.DS.'controllers'.DS.$this->folder.DS.'index.php');
		}
	}
	
	// Constructor - saves controller name and action.
	
	public function __construct($action, $args) {
		$this->folder = strtolower(get_class($this));
		$this->action = ($action) ? $action : 'index';
		$this->args = $args;
	}
	
	// Destructor - renders all contained views.
	
	public function __destruct() {
		foreach ($this->Views as $View) {
			$View->render();
		}
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $Views = array();
	
	protected $Cache;
	
	protected $args = array();
	
	protected $folder;
	protected $action;
	
	protected function redir() {
		$this->folder = 'home';
		$this->action = 'index';
		$this->execute();
	}
	
}

?>