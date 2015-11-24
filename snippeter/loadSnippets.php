<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();

	$pageNum = (int)$_POST['page'] - 1;
	$position = $pageNum * $numSnip;

	$data = array();

	$query = $con->prepare("select id, user_id, title, description, public from snippets limit ?,?");

	$query->bind_param("ii", $position, $numSnip);
	$query->execute();
	$query->store_result();
	$query->bind_result($snippet_id, $user_id, $title, $description, $public);

	$q = $con->prepare("select username from users where user_id = ?");
	while($query->fetch()){
		$q->bind_param("s", $user_id);
		$q->execute();
		$q->bind_result($username);
		$q->fetch();

		$data['user_id'][] = $user_id;
		$data['snippet_id'][] = $snippet_id;
		$data['username'][] = $username;
		$data['title'][] = $title;
		$data['description'][] = $description;
		$data['public'][] = $active;
	}
	$q->close();
	$query->close();

	echo json_encode($data);

?>