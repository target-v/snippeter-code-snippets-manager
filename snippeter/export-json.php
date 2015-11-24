<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();

	$query = $con->prepare("select user_id from users where username = ?");
	$query->bind_param("s", $_SESSION['user']);
	$query->execute();
	$query->bind_result($user_id);
	$query->fetch();
	$query->close();

	$data = array();

	$query = $con->prepare("select id, title, description, date, snippet, group_id from snippets where user_id = ?");
	$query->bind_param("s", $user_id);
	$query->execute();
	$query->bind_result($id, $title, $description, $date, $snippet, $group_id);
	while($query->fetch()){
		$data['id'][] = $id;
		$data['title'][] = $title;
		$data['description'][] = $description;
		$data['date'][] = $date;
		$data['snippet'][] = $snippet;
		$data['group_id'][] = $group_id;
	}
	$query->close();

	$tagArray = array();

	$query = $con->prepare("select tags from tags_snippets where snippets_id = ?");
	foreach($data['id'] as $tempId){
		$query->bind_param("s", $tempId);
		$query->execute();
		$query->bind_result($tags);
		while($query->fetch()){
			$tagArray[] = "#".$tags;
		}

		$tagsList = implode(", ", $tagArray);
		$data['tags'][] = $tagsList;
		unset($tagsList);
		unset($tagArray);
	}
	$query->close();

	$query = $con->prepare("select id, name, user_id from groups where user_id = ?");
	$query->bind_param("s", $user_id);
	$query->execute();
	$query->bind_result($id, $name, $user_id);
	while($query->fetch()){
		$data["groupId"][] = $id;
		$data["groupName"][] = $name;
		$data["groupUserId"][] = $user_id;
	}
	$query->close();

	$filename = uniqid() . ".json";
	
	header("Content-disposition: attachment; filename=$filename");
	header("Content-type: text/plain");

	echo json_encode($data);

?>