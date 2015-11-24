<?php

	$host = 'localhost';
	$username = 'root';
	$password = '';
	$database = 'snippeter';

	$pageRoot = 'http://localhost/snippeter'; // without " / " at the end
	$yourEmail = 'yourEmail@gmail.com';
	$numUsers = 20; // number of users per page in admin panel
	$numSnip = 20; // number of snippets per page in admin panel

	$con = new mysqli($host, $username, $password, $database);

	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", "Server error");
	    exit();
	}
	
?>
