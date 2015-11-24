<?php 
	session_start();
	if(isset($_COOKIE['user'])){
		$_SESSION['user'] = $_COOKIE['user'];
	}
	if(isset($_SESSION['user'])){
		header("Location: main.php");
	}
	
	if(isset($_COOKIE['lang'])){
		$_SESSION['lang'] = $_COOKIE['lang'];
	}

	include 'langCheck.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $lang['pageTitle']; ?></title>
	<meta charset="utf-8">
    <link href='http://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/zoom.css">
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/script.js" type="text/javascript"></script>
    <script src="jquery.clipboard.js"></script>
    <script src="js/index.js"></script>
    <script src="js/zoom.js"></script>
</head>
<body>
<label hidden id="langHolder"><?php if(isset($_SESSION['lang'])) echo $_SESSION['lang']; else echo "en";?></label>
<div class="flagsWrap">
	<img id="bs" <?php if($_SESSION['lang'] == 'bs') echo "class='activeFlag'";?> src="img/bs.jpg">
	<img id="ro" <?php if($_SESSION['lang'] == 'ro') echo "class='activeFlag'";?> src="img/ro.jpg">
	<img id="en" <?php if($_SESSION['lang'] == 'en') echo "class='activeFlag'";?> src="img/en.jpg">
	<img id="de" <?php if($_SESSION['lang'] == 'de') echo "class='activeFlag'";?> src="img/de.jpg">
</div>

<div class="main-wrap" style="text-align: center" >
	<a href="index.php"><label class="main-label"><?php echo $lang['pageTitle']; ?></label></a>
	<div class="index-wrap">
		<a id="image1"><img src="img/image1.png"></a>
		<a id="image2"><img src="img/image2.png"></a>
		<a id="image3"><img src="img/image3.png"></a>
		
		<br><br>

		<a id="login-button" class="buttons" href="#"><?php echo $lang['login']; ?></a>
		<a id="register-button" class="buttons" href="register.php"><?php echo $lang['register']; ?></a>

	</div>

	<a href="http://codecanyon.net/user/alenn/portfolio?ref=alenn" target="_blank"><img id="ccLogo" src="img/cc.png" style="position: relative; top: 250px;"></a>

	<div class="login-wrap" style="display: none;">
		<form action="login-data.php" method="post" class="login-form">
			<input type="text" name="username" placeholder="<?php echo $lang['username']; ?>" id="input-username"><br>
			<input type="password" name="password" placeholder="<?php echo $lang['password']; ?>" id="input-password"><br>
			<input type="checkbox" name="remember-me" value="1" id="input-remember"><label><?php echo $lang['rememberMe']; ?></label>
			<a href="#" id="forgot-pass-link"><?php echo $lang['forgotPassword']; ?></a>
			<input type="submit" value="<?php echo $lang['loginPlaceholder']; ?>" id="submit-button-login">
		</form>

		<div class="login-errors">
			
		</div>
	</div>

	<div class="reset-wrap" style="display: none;">
		<form action="reset-password.php" method="post" class="reset-form">
			<input type="text" name="email" placeholder="<?php echo $lang['emailPlaceholder']; ?>" id="reset-email">
			<input type="submit" value="<?php echo $lang['resetSubmit']; ?>" id="reset-submit">
		</form>

		<div class="reset-errors">
		</div>
	</div>
</div>
</body>
</html>