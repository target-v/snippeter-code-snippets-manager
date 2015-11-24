<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();
	
	$ids = $_POST['ids'];
	
	$query = $con->prepare("delete from snippets where id = ?");

	foreach($ids as $id){
		$con->query("delete from tags_snippets where snippets_id = '$id'");
		$query->bind_param("s", $id);
		$query->execute();
	}
	$query->close();
	echo 'ok';
?>