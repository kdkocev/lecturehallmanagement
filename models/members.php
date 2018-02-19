<?php
	include_once 'system/model.php';

	class Member extends Model {
		public $id;
		public $name;
		public $email;
		public $faculty_number;
		public $is_admin;
		
		function __construct($arr) {
			$this->id = $arr['id'];
			$this->name = $arr['name'];
			$this->email = $arr['email'];
			$this->faculty_number = $arr['faculty_number'];
			$this->is_admin = $arr['is_admin'];
		}

		function render() {
			return $this->name . " " . $this->faculty_number;
		}

		public static function objects() {
			return new MemberManager();
		}
	}

	class MemberManager extends Manager {
		public $table = 'members';
		
		public function getById($id) {
			$id = $this->escape($id);
			$table = $this->table;
			$sql = "SELECT * FROM $table WHERE id=$id";
			$db_res = $this->db->query($sql);
			$res = [];
			foreach($db_res as $row) {
				$res[] = new Member($row);
			}
			return $res[0];
		}

		public function getByEmail($email) {
			$table = $this->table;
			$email = $this->escape($email);
			$sql = "SELECT * FROM $table WHERE `email`='$email'";
			$db_res = $this->db->query($sql);
			$res = [];
			foreach($db_res as $row) {
				$res[] = new Member($row);
			}
			return $res[0];
		}

		public function is_login_correct($email, $password) {
			$table = $this->table;
			$email = $this->escape($email);
			$password = md5($this->escape($password));
			$sql = "
				SELECT * FROM $table WHERE `email`='$email' AND `password`='$password' AND `is_active`=1;
			";

			/* TODO: perhaps return the login failure reason.
				e.g. "User not activated", "Wrong email or password" etc.
			*/

			$db_res = $this->db->conn->query($sql);
			
			return mysqli_num_rows($db_res) == 1;
		}
	}