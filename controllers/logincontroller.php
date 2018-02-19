<?php
	include_once "models/members.php";

	class LoginController extends Controller {
		function get() {
			return $this->render("login.tpl");
		}

		function post() {
			$email = $_POST['email'];
			$password = $_POST['password'];
			$error = "";

			if(Member::objects()->is_login_correct($email, $password)) {
				$user = Member::objects()->getByEmail($email);

				$_SESSION['User'] = array(
					'id' => $user->id,
					'email' => $user->email,
					'is_admin' => $user->is_admin
				);
			} else {
				$error = "Login incorrect";
			}

			$config = include 'config.php';

			if($error != "") {
				header("Location: " . $config['base_path'] . "?error=$error");
			} else {
				header("Location: " . $config['base_path']);
			}
		}

		function logout() {
			session_destroy();
			$config = include 'config.php';
			header("Location: ".$config['base_path']."/login");
		}
	}