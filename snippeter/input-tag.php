<?php
	include 'database/connect.php';

	$tagsArray = $_POST['tags'];
	$tagsArray = json_decode($tagsArray);
	$stmt = $con->prepare("insert ignore into tags values(?)");
	foreach($tagsArray as $tag){
		echo $tag;
		$stmt->bind_param("s", $tag);
		$stmt->execute();
	}
	$stmt->close();

?>