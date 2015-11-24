<?php 
	session_start();
	if(isset($_COOKIE['admin'])){
		$_SESSION['admin'] = $_COOKIE['admin'];
	}
	if(isset($_SESSION['admin'])){
		header("Location: admin-main.php");
	}
	if(isset($_COOKIE['lang'])){
		$_SESSION['lang'] = $_COOKIE['lang'];
	}
	include 'langCheck.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $lang['pageTitle']; ?> - <?php echo $lang['adminPanel']; ?></title>
	<meta charset="utf-8">
    <link href='http://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/admin-main.css">
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/admin-script.js" type="text/javascript"></script>
</head>
<body>
<label hidden id="langHolder"><?php if(isset($_SESSION['lang'])) echo $_SESSION['lang']; else echo "en";?></label>
<div class="flagsWrap">
	<img id="bs" <?php if($_SESSION['lang'] == 'bs') echo "class='activeFlag'";?> src="img/bs.jpg">
	<img id="ro" <?php if($_SESSION['lang'] == 'ro') echo "class='activeFlag'";?> src="img/ro.jpg">
	<img id="en" <?php if($_SESSION['lang'] == 'en') echo "class='activeFlag'";?> src="img/en.jpg">
	<img id="de" <?php if($_SESSION['lang'] == 'de') echo "class='activeFlag'";?> src="img/de.jpg">
</div>
<div class="admin-full-wrap" style="text-align: center;">
	<a href="admin-login.php"><label class="main-label"><?php echo $lang['adminPanel']; ?></label></a>
	<div class="admin-wrap" >
		<form action="admin-login-data.php" method="post" class="admin-login-form">
			<input type="text" name="username" placeholder="<?php echo $lang['username']; ?>" id="admin-input-username"><br>
			<input type="password" name="password" placeholder="<?php echo $lang['password']; ?>" id="admin-input-password"><br>
			<input type="checkbox" name="remember-me" value="1" id="input-remember"><label><?php echo $lang['rememberMe']; ?></label>
			<a href="#" id="admin-forgot-pass-link"><?php echo $lang['forgotPassword']; ?></a>
			<input type="submit" value="<?php echo $lang['login']; ?>" id="admin-submit-button">
		</form>

		<div class="admin-login-errors">
			
		</div>
	</div>

	<div class="reset-wrap" style="display: none;">
		<form action="reset-password.php" method="post" class="reset-form">
			<input type="text" name="email" placeholder="<?php echo $lang['email']; ?>" id="admin-reset-email">
			<input type="submit" value="<?php echo $lang['submit']; ?>" id="admin-reset-submit">
		</form>

		<div class="admin-reset-errors">
		</div>
	</div>
</div>
</body>
</html>