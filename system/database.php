<?php
	class DB {
		public static $instance = null;
		public $conn;
		function __construct() {
			// TODO: This should be a singleton
			//if(!is_null(DB::$instance)) {
			//	return DB::$instance;
			//}
			
			$configs = require("config.php");

			$servername = $configs['database']['servername'];
			$username = $configs['database']['username'];
			$password = $configs['database']['password'];
			$db_name = $configs['database']['db_name'];
			
			$this->conn = new mysqli($servername, $username, $password, $db_name);
			if ($this->conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			DB::$instance = $this;
			// echo "Connected successfully";
		}
		
		public function query($sql) {
			$q = $this->conn->query($sql);
			$res = [];
			if(is_bool($q)) return $q;
			while($row = $q->fetch_assoc()) {
				$res[] = $row;
				}
			return $res;
		}
	}