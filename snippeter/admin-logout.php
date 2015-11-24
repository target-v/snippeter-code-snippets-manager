<?php

	session_start();
	unset($_SESSION['admin']);
	$week = time()-302400; // cookie is set for one week, if you change this value,
	                       // make sure you change it in logout.php too
	setcookie('admin', $user, $week);

	include 'database/connect.php';
	session_start();
	$session = session_id();

	$query = $con->prepare("delete from user_online where session = ?");
	$query->bind_param("s", $session);
	$query->execute();
	$query->close();

	header("Location: admin-login.php");

?>