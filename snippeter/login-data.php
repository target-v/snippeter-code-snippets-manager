<?php

	session_start();

	include 'functions.php';
	include 'database/connect.php';
	include 'langCheck.php';

	$user = $_POST['username'];
	$pass = $_POST['password'];
	if(isset($_POST['remember-me'])) $rem = $_POST['remember-me'];

	if(empty($user)){
		echo "<label>" . $lang['usernameEmpty']. "</label>";
		exit();
	}
	if(empty($pass)){
		echo "<label>" . $lang['passwordEmpty']. "</label>";
		exit();
	}

	if($load = $con->prepare("select * from users where username = ? and password = ?")){
		$pass = encrypt($_POST['password']);
		$load->bind_param("ss", $user, $pass);
		$load->execute();
		$load->store_result();
		$number = $load->num_rows;
		$load->close();
	}
	
	if($number === 0){
		echo "<label>" . $lang['userPassNotCorrect']. "</label>";
		exit();
	}else{
		if($active = $con->prepare("select active, banned from users where username = ?")){
			$active->bind_param("s", $user);
			$active->execute();
			$active->bind_result($res, $banned);
			$active->fetch();
		}
		
		if($res === 1 && $banned === 0){
			if($rem === "1"){
				$week = time()+302400;
				setcookie('user', $user, $week);
			}
			echo "ok";
			$_SESSION['user'] = $user;
			exit();
		}else{
			if($banned === 1){
				echo "<label>" . $lang['bannedAccount']. "</label>";
				exit();
			}
			echo "<label>" . $lang['notActivated']. "</label>";
			exit();
		}
	}
?>