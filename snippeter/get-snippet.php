<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();

	$id = $_POST['id'];
	$flag = $_POST['flag'];

	$query = $con->prepare("select user_id from users where username = ?");
	$query->bind_param("s", $_SESSION['user']);
	$query->execute();
	$query->bind_result($user_id);
	$query->fetch();
	$query->close();

	if($flag === 'true'){
		$query = $con->prepare("select snippet from snippets where id = ? and user_id = ?");
		$query->bind_param("ss", $id, $user_id);
		$query->execute();
		$query->bind_result($code);
		$query->fetch();
		$query->close();

		echo htmlspecialchars($code, ENT_QUOTES, "UTF-8");
	}else{
		$query = $con->prepare("select title, description, snippet from snippets where id = ? and user_id = ?");
		$query->bind_param("ss", $id, $user_id);
		$query->execute();
		$query->bind_result($title, $description, $snippet);
		$query->fetch();
		$query->close();
		$array = array();
		$array['title'] = $title;
		$array['description'] = $description;
		$array['snippet'] = $snippet;
		echo json_encode($array, JSON_HEX_QUOT | JSON_HEX_TAG);
	}


?>