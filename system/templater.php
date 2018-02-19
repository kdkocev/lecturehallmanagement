<?php
	// This is here to suppress the warning from strtotime
	$configs = include 'config.php';
	date_default_timezone_set($configs['date_default_timezone_set']);

	class Template {
		private $template;
		
		function __construct($template = null) {
			if(isset($template)) {
				$this->load($template);
			}
		}
		
		public function load($template) {
			if (!is_file($template)) {
				throw new FileNotFoundException("File not found: $template");
			} elseif (!is_readable($template)) {
				throw new IOException("Could not access file: $template");
			} else {
				$this->template = $template;
			}
		}
		
		public function set($var, $content) {
			$this->$var = $content;
		}
		
		public function parse() {
			ob_start();
			require $this->template;
			$content = ob_get_clean();
			return $content;
		}
	}
