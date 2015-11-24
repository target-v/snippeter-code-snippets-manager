<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	$code = $_GET['code'];
	$user = $_GET['user'];

	if(!$code||!$user){
		$error = $lang['invalidLink'];
	}else{
		if($check = $con->prepare("select * from users where username = ? and code = ?")){
			$check->bind_param("ss", $user, $code);
			$check->execute();
			$check->store_result();
			$number = $check->num_rows;
			$check->close();
		}
		if($check2 = $con->prepare("select active from users where username = ?")){
			$check2->bind_param("s", $user);
			$check2->execute();
			$check2->bind_result($result);
			$check2->fetch();
			$check2->close();
		}
		
		if($result === 1){
			$error = $lang['accountActivated1'];
		}
		else if($number > 0){
			$con->query("update users set active = 1");
			$error = $lang['accountActivated2'];
		}
		else{
			$error = $lang['unableToActivate'];
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $lang['pageTitle']; ?></title>
	<meta charset="utf-8">
    <link href='http://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
    	setTimeout(function(){ window.location = 'index.php'; }, 3000);
    });
    </script>
</head>
<body>

<div class="main-wrap" style="text-align: center" >
	<label style="
		background-color: #2980B9;
	    border-radius: 9px;
	    box-shadow: 0 0 10px #2980B9;
	    color: #FFFFFF;
	    display: inline-block;
	    font-family: 'Lato';
	    height: 50px;
	    line-height: 50px;
	    position: relative;
	    top: 100px;
	    width: 300px;
	"><?php echo $error; ?></label>
</div>
</body>
</html>

