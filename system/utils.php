<?php
	function url($path, $controllerName) {
		$controller = explode('.', $controllerName);
		$method = "";
		if(sizeof($controller) > 1) {
			$method = $controller[1];
		}
		return array(
			"path" => $path,
			"controllerName" => $controller[0],
			"methodName" => $method
		);
	}