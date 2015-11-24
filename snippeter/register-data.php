<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	$username = $_POST['username'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$rpassword = $_POST['rpassword'];

	$userCheck = $con->prepare("select * from users where username = ?");
	$userCheck->bind_param("s", $username);
	$userCheck->execute();
	$userCheck->store_result();
	$nmb = $userCheck->num_rows;
	$userCheck->close();

	$mailCheck = $con->prepare("select * from users where email = ?");
	$mailCheck->bind_param("s", $email);
	$mailCheck->execute();
	$mailCheck->store_result();
	$mm = $mailCheck->num_rows;
	$mailCheck->close();

	$check = 0;

	

	if($nmb > 0){
		$errors[] = "<label>".$lang['userExists']."</label>";
		$check = 1;
	}
	if($mm > 0){
		$errors[] = "<label>".$lang['mailExists']."</label>";
		$check = 1;
	}
	if(empty($username)){
		$errors[] = "<label>".$lang['usernameEmpty']."</label>";
		$check = 1;
	}
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$errors[] = "<label>".$lang['emailInvalid']."</label>";
		$check = 1;
	}
	if(empty($password)){
		$errors[] = "<label>".$lang['passwordEmpty']."</label>";
		$check = 1;
	}
	if($password !== $rpassword){
		$errors[] = "<label>".$lang['passwordInvalid']."</label>";
		$check = 1;
	}
	if($check === 0){
		$password = encrypt($password);
		$date = date('Y-m-d');
		// ***send mail for validation***

		$code = uniqid();
		$to = $email; // email will be sent to this address
		$subject = "Activate your account"; //email subject
		$headers = "From: $yourEmail"; //this is your mail
		$body = "Hello $user\n\nYou registered and need to activate your mail. 
Please click on following link or paste it in your browser:\n\n
$pageRoot/activate.php?user=$username&code=$code\n\n
Thank you!";

		if(!mail($to, $subject, $body, $headers)){
			$errors[] = "<label>".$lang['mailError']."</label>";
		}
		else{

			if($std = $con->prepare("insert into users (username, password, email, code, joined) 
			values (?,?,?,?,?)")){
				$std->bind_param("sssss", $username, $password, $email, $code, $date);
				$std->execute();
				$std->close();
			}
			$errors[] = "<label>".$lang['successRegister']."</label>";
		}
	}
	echo json_encode($errors);
	
?>