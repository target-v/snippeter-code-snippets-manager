<?php
	ob_start();
	$errors = array();

	function protect(){
		if(!isset($_SESSION['user']) && !isset($_COOKIE['user'])){
			header("Location: index.php");
			exit();
		}	
	}

	function protectAdmin(){
		if(!isset($_SESSION['admin']) && !isset($_COOKIE['admin'])){
			header("Location: admin-login.php");
			exit();
		}	
	}

	function encrypt($pass){
		$salt = "jhkl2jh8f8s898we8ewiouq48484b";
		$password = sha1($salt . $pass);
		return $password;
	}

	function user_counter(){
		include 'database/connect.php';

		session_start();
		$session = session_id();
		$time = time();
		$time_check = $time-600;

		$query = $con->prepare("select * from user_online where session = ?");
		$query->bind_param("s", $session);
		$query->execute();
		$query->store_result();
		$nm = $query->num_rows;
		$query->close();

		if($nm == '0'){
			$query = $con->prepare("insert into user_online (session, time) values(?, ?)");
			$query->bind_param("ss", $session, $time);
			$query->execute();
			$query->close();
		}else{
			$query = $con->prepare("update user_online set time = ? where session = ?");
			$query->bind_param("ss", $time, $session);
			$query->execute();
			$query->close();
		}

		$query = $con->prepare("delete from user_online where time < ?");
		$query->bind_param("s", $time_check);
		$query->execute();
		$query->close();

		$query = $con->prepare("select count(*) from user_online");
		$query->bind_result($userCount);
		$query->execute();
		$query->fetch();
		$query->close();

		return $userCount;
	}
?>