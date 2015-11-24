<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();

	$value = $_POST['value'];
	$id = $_POST['id'];

	$query = $con->prepare("select user_id from users where username = ?");
    $query->bind_param("s", $_SESSION['user']);
    $query->execute();
    $query->bind_result($user_id);
    $query->fetch();
    $query->close();

	$query = $con->prepare("update snippets set public = ? where user_id = ? and id = ?");
	$query->bind_param("sss", $value, $user_id, $id[0]);
	$query->execute();
	$query->close();

?>