<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();
	
	$ids = $_POST['ids'];
	$flag = $_POST['flag'];

	if($flag == 'ban'){
		$query = $con->prepare("update users set banned = 1 where user_id = ?");
		foreach($ids as $id){
			$query->bind_param("s", $id);
			$query->execute();
		}
		$query->close();
		echo 'ok';
	}else if($flag == 'unban'){
		$query = $con->prepare("update users set banned = 0 where user_id = ?");
		foreach($ids as $id){
			$query->bind_param("s", $id);
			$query->execute();
		}
		$query->close();
		echo 'ok';
	}else{
		echo 'error';
	}
	
?>