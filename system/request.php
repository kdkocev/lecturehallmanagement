<?php
	class Request {
		function __construct($server) {
			$this->full_request_uri = $server['REQUEST_URI'];
			$this->url = $this->cleanup_request_uri($server['REQUEST_URI']);
			$this->method = $server['REQUEST_METHOD'];
		}

		private function cleanup_request_uri($uri) {
			$configs = require("config.php");
			$res = substr($uri, strlen($configs['base_path']));
			if($res == false) {
				return "";
			}
			$url = explode("?", $res);
			$res = $url[0];
			return $res;
		}
	}
