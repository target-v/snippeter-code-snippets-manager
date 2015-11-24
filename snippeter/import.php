<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	protect();

	$query = $con->prepare("select user_id from users where username = ?");
	$query->bind_param("s", $_SESSION['user']);
	$query->execute();
	$query->bind_result($user_id);
	$query->fetch();
	$query->close();

	$uploaddir = 'tmp/';
	$uploadfile = $uploaddir . basename($_FILES['file']['name']);


	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
	    
		$json = json_decode(file_get_contents($uploadfile), true);

		if($json && $json['id'] && $json['title'] && $json['description'] && $json['snippet'] && $json['date'] && $json["group_id"] && $json["tags"] && $json["groupId"] && $json["groupName"] && $json["groupUserId"]){
			$query = $con->prepare("insert into snippets (title, description, snippet, user_id, date, group_id) values (?, ?, ?, ?, ?, ?)");
			$query2 = $con->prepare("insert into tags_snippets values ('', ?, ?, ?)");
			$q3 = $con->prepare("insert into groups values (?, ?, ?)");

			for($i = 0; $i < count($json["groupName"]); $i++){
				$q3->bind_param("sss", $json["groupId"][$i], $json["groupName"][$i], $json["groupUserId"][$i]);
				$q3->execute();
			}
			$q3->close();

			for($i = 0; $i < count($json['id']); $i++){
				$query->bind_param("ssssss", $json['title'][$i], $json['description'][$i], $json['snippet'][$i], $user_id,$json['date'][$i], $json["groupId"][$i]);
				$query->execute();
				$id = $con->insert_id;
				$tagsList = explode(", ", $json['tags'][$i]);
				foreach($tagsList as $tag){
					$query2->bind_param("sss", $id, substr($tag, 1), $user_id);
					$query2->execute();
				}
			}

			$query->close();
			$query2->close();
			unlink($uploadfile);
			echo 'ok';
		}else{
			echo $lang['invalidJsonFile'];
			unlink($uploadfile);
		}
		
	}else{
	    echo $lang['uploadError'];
	}

	      
?>