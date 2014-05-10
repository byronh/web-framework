<?php

class View {
	
	/* * * *
	 PUBLIC
	* * * */
	
	// Constructor - allows view file to access data array keys as variable names.
	
	public function __construct($path, $data = array()) {
		$this->data = $data;
		$this->path = $path;
		if (strpos($path, '.') === false) {
			$this->path .= '.php';	
		}
	}
	
	// Sets a variable that can be accessed locally within the view.
	
	public function set($variable, $value) {
		$this->data[$variable] = $value;
	}
	
	// Displays the HTML output directly on the page.
	
	public function render() {
		
		extract($this->data);
		
		if (file_exists(ROOT.DS.'app'.DS.'views'.DS.$this->path))
			require(ROOT.DS.'app'.DS.'views'.DS.$this->path);
		elseif (file_exists(ROOT.DS.'vault'.DS.'views'.DS.$this->path))
			require(ROOT.DS.'vault'.DS.'views'.DS.$this->path);
		else throw new ViewException($this->path.' not found.');
	}
	
	// Returns HTML output from this view as a string.
	
	public function output() {
	
		extract($this->data);
		if (file_exists(ROOT.DS.'app'.DS.'views'.DS.$this->path))
			$_path = ROOT.DS.'app'.DS.'views'.DS.$this->path;
		elseif (file_exists(ROOT.DS.'vault'.DS.'views'.DS.$this->path))
			$_path = ROOT.DS.'vault'.DS.'views'.DS.$this->path;
		else {
			throw new ViewException($this->path.' not found.');
			return;
		}
		
		ob_start();
		if (file_exists($_path)) require($_path);
		$_output = ob_get_contents();
		ob_end_clean();
		return $_output;
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $data = array();
	protected $path;
	
	// Allows recursive view embedding.
	// For each recursive call your array must have one additional associative dimension.
	
	protected function loadview($View, $data = array()) {
		if ($View instanceof View) {
			$View->render();
		} elseif ($View instanceof Viewable) {
			$result = $View->getview();
			if (is_array($result)) {
				foreach ($result as $singleview)
					$singleview->render();
			} else {
				$result->render();
			}
		} else {
			$output = new View($View, $data);
			$output->render();
		}
		return $this;
	}
	
}

?>