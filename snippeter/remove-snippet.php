<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();

	$id = $_POST['id'];

	if(!empty($id)){
		$query = $con->prepare("delete from tags_snippets where snippets_id = ?");
		$query->bind_param("s", $id);
		$query->execute();
		$query->close();

		$query = $con->prepare("delete from snippets where id = ?");
		$query->bind_param("s", $id);
		$query->execute();
		$query->close();

		echo "ok";
		exit();
	}else{
		echo "break";
		exit();
	}
?>