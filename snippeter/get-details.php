<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();;
	
	$id = $_POST['id'];

	$query = $con->prepare("select user_id from users where username = ?");
	$query->bind_param("s", $_SESSION['user']);
	$query->execute();
	$query->bind_result($user_id);
	$query->fetch();
	$query->close();
	
	$data = array();
	$temp = array();

	$query = $con->prepare("select id, title, description, date, public from snippets where id = ? and user_id = ?");
	$query->bind_param("ss", $id, $user_id);
	$query->execute();
	$query->bind_result($snippetId, $title, $description, $date, $public);
	$query->fetch();
	$query->close();

	$data['idSnippet'][] = $snippetId;
	$data['title'][] = $title;
	$data['description'][] = $description;
	$data['date'][] = $date;
	$data['public'][] = $public;

	$query = $con->prepare("select tags from tags_snippets where user_id = ? and snippets_id = ?");
	$query->bind_param("ss", $user_id, $id);
	$query->execute();
	$query->bind_result($tag);
	while($query->fetch()){
		$temp[] = "#".$tag;
	}
	$query->close();
	$tagsList = implode(", ", $temp);
	$data['tags'][] = $tagsList;

	echo json_encode($data);
	exit();

?>