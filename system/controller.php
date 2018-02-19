<?php
	include_once 'templater.php';

	class Controller {
		public function render($template, $data = array()) {
			$templates_path = 'templates/';
			$t = new Template($templates_path . $template);

			foreach($data as $k => $v) {
				$t->set($k, $v);
			}

			$configs = include 'config.php';

			$t->set('configs', $configs['templates']);

			return $t->parse();
		}
	}
