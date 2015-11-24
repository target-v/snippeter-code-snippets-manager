<?php session_start();?>
<?php 
	include 'langCheck.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $lang['pageTitle']; ?> - <?php echo $lang['registerPage']; ?></title>
	<meta charset="utf-8">
	<link href='http://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/script.js" type="text/javascript"></script>
    <script src="jquery.clipboard.js"></script>
</head>
<body style="background-color: white;">

<div class="register-wrap">
	<a href="index.php"><label class="main-label"><?php echo $lang['pageTitle']; ?></label></a>
	<form action="register-data.php" method="post" class="register-form">
		<input type="text" name="username" placeholder="<?php echo $lang['username']; ?>" id="username-register"><br>
		<input type="text" name="email" placeholder="<?php echo $lang['email']; ?>" id="email-register"><br>
		<input type="password" name="password" placeholder="<?php echo $lang['password']; ?>" id="password-register"><br>
		<input type="password" name="rpassword" placeholder="<?php echo $lang['repeatPassword']; ?>" id="rpassword-register"><br>
		<input type="submit" value="<?php echo $lang['registerSubmit']; ?>" id="submit-button">
	</form><br>

	<div class="error">

	</div>
</div>
</body>