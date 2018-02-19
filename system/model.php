<?php
	class Model {
		protected $db;
		public function __construct() {
			include_once 'system/database.php';
			$this->db = new DB();
		}
		
	}
	
	class Manager {
		protected $db;
		function __construct() {
			include_once 'system/database.php';
			$this->db = new DB();
		}

		public function escape($str) {
			return mysqli_real_escape_string($this->db->conn, $str);
		}
	}