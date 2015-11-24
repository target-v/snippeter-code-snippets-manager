<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();

	$t = $_POST['user'];
	$flag = $_POST['flag'];

	$text = "%".$t."%";

	$data = array();
	if($flag == 'no')
		$query = $con->prepare("select user_id, username, email, joined, active from users where username like ? and banned = '0' limit ?");
	else if($flag == 'yes')
		$query = $con->prepare("select user_id, username, email, joined, active from users where username like ? and banned = '1' limit ?");
	$query->bind_param("ss", $text, $numUsers);
	$query->execute();
	$query->bind_result($user_id, $user, $mail, $joined, $active);
	while($query->fetch()){
		$data['user_id'][] = $user_id;
		$data['username'][] = $user;
		$data['email'][] = $mail;
		$data['joined'][] = $joined;
		$data['active'][] = $active;
	}
	$query->close();

	echo json_encode($data);

?>