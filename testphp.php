<?php

//testlogin

	require_once("phpauthent/phpauthent_core.php");
	require_once("phpauthent/phpauthent_config.php");
	require_once("phpauthent/phpauthentadmin/locale/".$phpauth_language);
	//require('_config.php');
	//require ('_functions_common.php');
//protect page
	$usersArray  = array();
	$groupsArray = array("admin","supervisor","employee");
	pageProtect($usersArray,$groupsArray);	

	$user 			= trim($_COOKIE['USERNAME']);
	
	echo $user;
	
	if (isUserLogged()){
		echo "User logged";
		echo getUserId();
		echo getUserName();
		
		echo "<br>";
		echo trim($_COOKIE['USERNAME']);
	}

	echo "<br>done";
	
?>