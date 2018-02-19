<?php
	class Router {
		function __construct($routes) {
			$this->routes = $routes;
		}
		
		public function route($request) {
			$controller = $this->get_controller($request);
			if(!empty($this->routes[$request->url]['methodName'])) {
				$method = $this->routes[$request->url]['methodName'];
			} else {
				$method = $this->supported_methods_mapping[$request->method];
			}
			
			if(!method_exists($controller, $method)) {
				return $this->route_not_found();
			}
			
			// Call the controller method that handles the current request
			return $controller->$method();
		}
		
		private function route_not_found() {
			// TODO: return real 404 or directly redirect to 404 and handle it there
			// Also when in debug mode - show comprehensive messages
			die("404 Page not found");
		}
		
		private function get_controller($request) {
			if(is_null($this->routes[$request->url])) {
				return $this->route_not_found();
			}
			
			include ($this->routes[$request->url]['path']);
			return new $this->routes[$request->url]['controllerName'];
		}
		
		private $supported_methods_mapping = array(
			'GET' => 'get',
			'POST' => 'post',
		);
	}