<?php

session_start();

if(isset($_SESSION['lang'])){
	$languages = array("bs", "en", "de", "ro");
	if(in_array($_SESSION['lang'], $languages)){
		$langName = $_SESSION['lang'];
		include "lang/$langName.php";
	}else{
		include "lang/en.php";
	}
}else{
	include "lang/en.php";
}