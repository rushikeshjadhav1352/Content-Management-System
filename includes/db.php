<?php

	// define database parameters as an array
	// $db = array('db_host'=>'localhost',
	// 						'db_user'=>'',
	// 						'db_pass'=>'',
	// 						'db_name'=>'');
	
	// loop thru the array to make them into constants
	// foreach($db as $key=>$value) {
	// 	define(strtoupper($key), $value);
	// }

	// connect to database
	$connection = mysqli_connect('localhost','root','','cms');
	
	 //define other constants
	define('SITENAME', 'HELLO', false);
	define('SITESUBTITLE', '&nbsp;&nbsp;&nbsp;Shooting Craps in Sin City', false);
	define('POSTSPERPAGE', 10);
	define('AUTHOR', 'Rushikesh jadhav', false);
	define('TIMEOUT', 120);
	define('HASHCOST', 12);
	define('TZ', 'Asia/Kolkata');

?>
