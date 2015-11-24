<?php

	$lang = $_POST['lang'];

	$expire=time()+60*60*24*30;
	setcookie("lang", $lang, $expire);
	exit();

?>