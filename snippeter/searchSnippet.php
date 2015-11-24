<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();

	$t = $_POST['title'];

	$text = "%".$t."%";

	$data = array();
	$query = $con->prepare("select id, user_id, title, description, public from snippets where title like ? limit ?");
	$query->bind_param("ss", $text, $numSnip);
	$query->execute();
	$query->store_result();
	$query->bind_result($id, $user_id, $title, $description, $public);
	$q = $con->prepare("select username from users where user_id = ?");
	while($query->fetch()){
		$q->bind_param("s", $user_id);
		$q->execute();
		$q->bind_result($username);
		$q->fetch();

		$data['snippet_id'][] = $id;
		$data['user_id'][] = $user_id;
		$data['username'][] = $username;
		$data['title'][] = $title;
		$data['description'][] = $description;
		$data['public'][] = $public;
	}
	$q->close();
	$query->close();

	echo json_encode($data);

?>