<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();

	$group_id = $_POST['groupId'];
	$query = $con->prepare("select user_id from users where username = ?");
	$query->bind_param("s", $_SESSION['user']);
	$query->execute();
	$query->bind_result($user_id);
	$query->fetch();
	$query->close();

	$snippetArray = array();
	$query = $con->prepare("select id from snippets where group_id = ? order by id desc");
	$query->bind_param("s", $group_id);
	$query->execute();
	$query->bind_result($snippet_id);
	while($query->fetch()){
		$snippetArray[] = $snippet_id;
	}
	$query->close();

	$snippetData = array();
	$query = $con->prepare("select id, title from snippets where id = ? and user_id = ?");
	foreach($snippetArray as $snippet){
		$query->bind_param("ss", $snippet, $user_id);
		$query->execute();
		$query->bind_result($snippetId, $title);
		$query->fetch();
		$snippetData['snippetId'][] = $snippetId;
		$snippetData['title'][] = $title;
	}
	$query->close();
	echo json_encode($snippetData);

?>