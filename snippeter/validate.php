<?php

	session_start();

	include 'database/connect.php';
	include 'langCheck.php';

	$var = "";
	if(empty($_GET['user']) || empty($_GET['code']) || empty($_GET['mail'])){
		$var = $lang['linkNotValid'];
	}else{
		$user_id = $_GET['user'];
		$code = $_GET['code'];
		$mail = $_GET['mail'];
		$query = $con->prepare("select * from users where user_id = ? and code = ?");
		$query->bind_param("ss", $user_id, $code);
		$query->execute();
		$query->store_result();
		$num = $query->num_rows;
		$query->close();

		if($num !== 0){
			$query = $con->prepare("update users set email = ? where user_id = ?");
			$query->bind_param("ss", $mail, $user_id);
			$query->execute();
			$query->close();
			$var = $lang['yourEmailIs'].": $mail";
		}else{
			$var = $lang['emailNotChanged'];
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
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/script.js" type="text/javascript"></script>
</head>
<body>

<div class="wrap">
	<label id="display-status"><?php echo $var; ?></label>
	<a href="index.php"><label class="main-label"><?php echo $lang['pageTitle']; ?></label></a>
	<div></div>
	<div></div>
	<div></div>

	<br><br>

	<a id="login-button" class="buttons" href="#"><?php echo $lang['login']; ?></a>
	<a id="register-button" class="buttons" href="register.php"><?php echo $lang['register']; ?></a>
</div>

<div class="login-wrap" style="display: none;">
	<a href="index.php"><label class="main-label">Snippeter</label></a>
	<form action="login-data.php" method="post" class="login-form">
		<input type="text" name="username" placeholder="<?php echo $lang['username']; ?>" id="input-username"><br>
		<input type="password" name="password" placeholder="<?php echo $lang['password']; ?>" id="input-password"><br>
		<input type="checkbox" name="remember-me" value="1"><label><?php echo $lang['rememberMe']; ?></label>
		<a href="#"><?php echo $lang['forgotPassword']; ?></a>
		<input type="submit" value="<?php echo $lang['login']; ?>" id="submit-buttom">
	</form>
</div>

</body>
</html>