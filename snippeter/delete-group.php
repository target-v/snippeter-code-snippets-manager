<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();
	
	$id = $_POST['id'];
	
	$query = $con->prepare("update snippets set group_id = null where group_id = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$query->close();

	$query = $con->prepare("delete from groups where id = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$query->close();

	echo 'ok';
?>