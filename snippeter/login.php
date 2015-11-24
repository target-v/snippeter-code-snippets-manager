<!DOCTYPE html>
<html>
<head>
	<title>Snippeter - Login Page</title>
	<meta charset="utf-8">
	<link href='http://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/script.js" type="text/javascript"></script>
</head>
<body style="background-color: white;">

<div class="login-wrap">
	<a href="index.php"><label class="main-label">Snippeter</label></a>
	<form action="login-data.php" method="post" class="login-form">
		<input type="text" name="username" placeholder="Username" id="input-username"><br>
		<input type="password" name="password" placeholder="Password" id="input-password"><br>
		<input type="checkbox" name="remember-me" value="1"><label>Remember me</label>
		<a href="#">Forgot password?</a>
		<input type="submit" value="Login" id="submit-buttom">
	</form>
</div>

</body>
</html>