<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	protect();

	$title = $_POST['name'];
	$desc = $_POST['description'];
	$snippet = $_POST['snippet'];
	$tagsArray = $_POST['tags'];
	$tagsArray = json_decode($tagsArray);
	$flag = $_POST['flag'];
	$snippetId = $_POST['id'];
	$group_id = $_POST['groups'];

	if(empty($title)){
		echo $lang['emptyTitle'];
		exit();
	}
	if(empty($snippet)){
		echo $lang['emptySnippet'];
		exit();
	}
	if(empty($tagsArray)){
		echo $lang['emptyTag'];
		exit();
	}

	if(empty($group_id)){
		echo "Select group";
		exit();
	}

	$query = $con->prepare("select user_id from users where username = ?");
	$query->bind_param("s", $_SESSION['user']);
	$query->execute();
	$query->bind_result($user_id);
	$query->fetch();
	$query->close();

	if($flag === 'false'){
		$date = date("Y-m-d");
		$stmt = $con->prepare("insert into snippets values('',?, ?, ?, ?, ?, '', ?)");
		$stmt->bind_param("ssssss", $title, $desc, $snippet, $user_id, $date, $group_id);
		$stmt->execute();
		$id = $con->insert_id;
		$stmt->close();

		$q = $con->prepare("insert into tags_snippets values('', ?, ?, ?)");
		foreach($tagsArray as $tag){
			$q->bind_param("sss", $id, $tag, $user_id);
			$q->execute();
		}
		$q->close();
		echo 'ok';
	}else if($flag === 'true'){
		
		$stmt = $con->prepare("update snippets set title = ?, description = ?, snippet = ?, group_id = ? where id = ?");
		$stmt->bind_param("sssss", $title, $desc, $snippet, $group_id, $snippetId);
		$stmt->execute();
		$stmt->close();

		$w = $con->query("delete from tags_snippets where snippets_id = '$snippetId'");
		$q = $con->prepare("insert into tags_snippets values('', ?, ?, ?)");
		foreach($tagsArray as $tag){
			$q->bind_param("sss", $snippetId, $tag, $user_id);
			$q->execute();
		}
		$q->close();

		echo 'ok';
	}
?>