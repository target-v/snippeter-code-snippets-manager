<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();

	$id = $_POST['id'];

	$query = $con->prepare("select group_id from snippets where id = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$query->bind_result($group_id);
	$query->fetch();
	$query->close();

	$query = $con->prepare("select id, name from groups where id = ?");
	$query->bind_param("s", $group_id);
	$query->execute();
	$query->bind_result($id, $name);
	$query->fetch();
	$query->close();

	$data = array();
	$data['id'] = $id;
	$data['name'] = $name;

	echo json_encode($data);

?>