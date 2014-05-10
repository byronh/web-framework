<?php

final class Router {
	
	/* * * *
	 PUBLIC
	* * * */
	
	// Constructor - imports config settings.
	
	public function __construct() {
		require(ROOT.DS.'config'.DS.'routing.php');
		$this->routes = $route;
		$this->reserved = $reserved;
	}
	
	// Instantiates a controller based on url and any routes defined in config.
	
	public function route($url = '') {
		
		$url = strtolower(str_replace('_', '', $url));
		$segments = $this->segmenturl($url);
		$route = $this->parseroute($segments);

		$controller = array_shift($route);
		
		if (!class_exists($controller) || in_array($controller, $this->reserved) || !is_subclass_of($controller, 'Controller'))
			$controller = $this->routes['_defaultcontroller'];
		
		$controller = ucfirst($controller);
		$action = array_shift($route);
		$args = $route;
		
		$controller = new $controller($action, $args);
		$controller->execute();
		
	}
	
	
	/* * * *
	 PRIVATE
	* * * */
	
	private $routes = array(), $reserved = array();
	
	// Parses the url array based on config settings.
	
	private function parseroute($segments) {
				
		$url = implode('/', $segments);
		
		if (isset($this->routes[$url])) {
			return explode('/', $this->routes[$url]);
		}
		
		foreach ($this->routes as $key => $val) {
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));
			if (preg_match('#^'.$key.'$#', $url)) {
				if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE) {
					$val = preg_replace('#^'.$key.'$#', $val, $url);
				}
				return explode('/', $val);
			}
		}
		
		return $segments;
	}
	
	// Separates the url and removes empty queries and extra slashes.
	
	private function segmenturl($url) {
		return array_filter(explode('/', $url));
	}
	
}

?>