<?php
// $Author: vincentarn $
// $Date: 2005/08/25 21:29:55 $
// $Id: phpauthent_core.php,v 1.13 2005/08/25 21:29:55 vincentarn Exp $
// $Revision: 1.13 $

// phpAuthent - A security module for PHP enabled web sites
// Copyright (C) 2005 Arnaud Vincent

// This file is part of phpAuthent.

// phpAuthent is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.

// phpAuthent is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

// Contact author at vincentarn@users.sourceforge.net
?>
<?php
session_start();
	//ini_set('display_errors',1); 
	//error_reporting(E_ALL);
	// First starts a session
	
	// Function that registers a user
	// This function should be called when a login is performed.
	// Returns true if the user was logged in, false otherwise.
	function registerUser($username,$password) {
		require "phpauthent_config.php";
		// Connect to the database and checks if the user / password
		// combinaison matches any existing database entry
		$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);

		if ($mysql_link->connect_errno) {
			die($mysql_link->connect_error);
		}
				
		//added by me 7 Jan 2016 to remove inactive users from logging in
		$query = "SELECT phpauthent_users.".$db_fld_users_id." FROM ".$db_tbl_users." JOIN employee_info ON phpauthent_users.id = employee_info.uid WHERE phpauthent_users.".$db_fld_users_username." LIKE '".$username."' AND phpauthent_users.".$db_fld_users_password." LIKE '".encrypt($password,$phpauthent_enckey)."' AND employee_info.status = 'Active' ";
		$r_query = $mysql_link->query($query);
		
		//if (mysql_num_rows($r_query) != 0) {deprecated
		if ($r_query->num_rows != 0) {
			//$query_row = mysql_fetch_array($r_query);
			$query_row = $r_query->fetch_array(MYSQLI_ASSOC);
			
			writeSessionUserIds($query_row[$db_fld_users_id], $username);
			// Writing connection date into database
			writeUserLoginDate($query_row[$db_fld_users_id]);
			// Increasing number of connections for this user
			writeUserConnection($query_row[$db_fld_users_id]);
			return true;
			exit;
		} else {
			return false;
			exit;
		}
	}
	
	// Statistics
	// Store user login date
	function writeUserLoginDate($userid) {
		require "phpauthent_config.php";

		//new mysqli code
		//$mysql_link = mysql_connect() or die("Failed to connect to MySQL");
		$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);

		if ($mysql_link->connect_errno) {
			die($mysql_link->connect_error);
		}
		
		// First check previous connections number
		$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_lastlogin."='".date("Y-m-d H:i:s")."' WHERE ".$db_fld_users_id."=".$userid;
		//$r_query = mysql_query($query);old query
		$r_query = $mysql_link->query($query);
		
		//if (mysql_affected_rows() <> 1) {
		if ($mysql_link->affected_rows != 1) {	
			// Error while updating. Return false
			return false;
		} else {
			return true;
		}
	}
	
	// Statistics
	// Increase the number of connections for a user
	function writeUserConnection($userid) {
		require "phpauthent_config.php";
		$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);

		if ($mysql_link->connect_errno) {
			die($mysql_link->connect_error);
		}
		
		$query = "SELECT ".$db_fld_users_connections." FROM ".$db_tbl_users." WHERE ".$db_fld_users_id."=".$userid;
		$r_query = $mysql_link->query($query);
		$query_row = $r_query->fetch_array(MYSQLI_ASSOC);
		
		$nb_connections = $query_row[$db_fld_users_connections];
		$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_connections."='".($nb_connections + 1)."' WHERE ".$db_fld_users_id."=".$userid;
		$r_query = $mysql_link->query($query);
		if ($mysql_link->affected_rows != 1) {
			// Error while updating. Return false
			return false;
		} else {
			return true;
		}
	}
	
	// Function that remove any user information from the session.
	// This function should be called when a logout is performed.
	function unregisterUser() {
		require "phpauthent_config.php";
		// Destroy variables that could pre-exist in the session
		unset($_SESSION['$sess_user_id']);
		unset($_SESSION['$sess_user_name']);
	}
	
	// Determines if a user is logged in phpauth
	function isUserLogged() {
		require "phpauthent_config.php";
		if ((isset($_SESSION['$sess_user_id'])) && (isset($_SESSION['$sess_user_name']))) {
			return true;
		} else {
			return false;
		}
	}
	// Function that registers a user in the session
	function writeSessionUserIds($id, $username) {
		require "phpauthent_config.php";
		// First unregister any existing login to purge the session variables
		unregisterUser();
		// Sets user data into the session
		$_SESSION['$sess_user_id']=$id;
		$_SESSION['$sess_user_name']=$username;
		
	}
	
	// Returns the currently logged user id
	function getUserId() {
		require_once "phpauthent_config.php";
		if (isUserLogged()) {
			return $_SESSION['$sess_user_id'];
		} else {
			return NULL;
		}
	}
	
	// Returns the currently logged user name
	function getUserName() {
		require_once "phpauthent_config.php";
		if (isUserLogged()) {
			return $_SESSION['$sess_user_name'];
		} else {
			return NULL;
		}
	}
	
	// Parses the groups array passed in parameters to check is the logged in user
	// belongs to one (or more) of them
	// Returns true if the user is found in at least one of the groups, false if not
	// (or no user logged in)
	function isSessionUserInGroup($groupsArray) {
		require "phpauthent_config.php";
		if ((!empty($admin_always_enabled)) && ($admin_always_enabled == true)) {
			// Always embed admin group in groups - Configuration option
			array_push($groupsArray, "admin");
		}
		return isUserInGroup(getUserId(), $groupsArray);
	}
	
	// Generic method to check if a user belongs to one of the groups specified in the parameter Array
	function isUserInGroup($user_id, $groupsArray) {
		require "phpauthent_config.php";
		$retval = false;		// Initializes the return value to false
		$current_group_id = 0;  // Basic initialization
		if ($user_id != Null) {
			//mysql_connect($db_hostname,$db_username,$db_password);
			//mysql_select_db($db_database);
			$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);

			if ($mysql_link->connect_errno) {
				die($mysql_link->connect_error);
			}
			
			// Browsing groups array
			for ($i = 0; $i <= count($groupsArray); $i++) {
				$current_group_name = "NULL";
				if (!empty($groupsArray[$i])) {
					$current_group_name = $groupsArray[$i];
				}
				// Look for group id
				$query = "SELECT ".$db_fld_groups_id." FROM ".$db_tbl_groups." WHERE ".$db_fld_groups_name." LIKE '".$current_group_name."'";
				$r_query = $mysql_link->query($query);
				while ($row = $r_query->fetch_array(MYSQLI_ASSOC)) {	
					$current_group_id = $row[$db_fld_groups_id];
				}
				// Look in the relation table if the user id matches this group
				$query = "SELECT ".$db_fld_relation_id." FROM ".$db_tbl_relation." WHERE ".$db_fld_relation_uid."=".$user_id." AND ".$db_fld_relation_gid."=".$current_group_id;
				$r_query = $mysql_link->query($query);
				
//may need to check this
				if ($r_query != false) {
				//if(mysqli_num_rows($result)) {	
					while ($row = $r_query->fetch_array(MYSQLI_ASSOC)) {
						$retval = true;
					}
				}
			}
		}
		return $retval;
	}
		
	// Function that determines if the user logged in can perform an action.
	// If no user if logged in, the function returns FALSE.
	// If a user is logged in, the function returns TRUE whether it is in the
	// users list or in belongs to one of the groups specified in the groups array.
	function isEnabled($usersArray, $groupsArray) {
		require "phpauthent_config.php";
		if (!isUserLogged()) {
			return false;
		} else {
			// Checks first if the logged in user is in the users array or is in one of the groups
			if (in_array($_SESSION['$sess_user_name'],$usersArray)) {
				return true;
			}
			if (isSessionUserInGroup($groupsArray)) {
				return true;
			}
			return false;
		}
	}

	// ----------------------------------------------------------------------
	// Function(s)/Description:
	// ========================
	// encodeURL/decodeURL - two functions to encode and decode URLs to
	// preserve the original URL with embedded '?' and '&' characters.
	// This is necessary so that complex URL's that contain multiple '&'
	// characters will not be mis-interpretted as parameters for phpauthent.
	//
	// Solution approach:
	// ==================
	// During the encoding phase, a start marker of '~1' if placed at the start
	// of the URL.  An ending marker of '~2' is placed at the end of the URL.
	// The tokens used are shown in the Token Xref Table.  The decoding phase
	// reverses the process replacing tokens with the original chars
	// 
	//	     Token Xref Table
	//	============================
	//	~1	-	Start of URL
	//	~2	-	End of URL
	//	~3	-	'?'
	//	~4	-	'&'
	// ----------------------------------------------------------------------

	// encodeURL - encodes the url by replacing the '?' and '&' chars with
	// '~#' tokens so complex URL's can be preserved.  The URL is prepended
	// with '~1' and appended on the end with '~2'.
	function encodeURL ($url)
	{
		// replace '?'
		$url = str_replace ("?", "~3", $url);

		// replace '&'
		$url = str_replace ("&", "~4", $url);

		// append the start and end tokens
		$url = "~1" . $url . "~2";
		return ($url);
	}

	// decodeURL - decodes the url by replacing the '~#' tokens with
	// appropriate chars so complex URL's can be restored.  The URL must have
	// the start and end tokens or it will be considered a non-encoded URL
	// and will not be decoded.
	function decodeURL ($url)
	{
		// if the start and end tokens are missing, then this was not an encoded URL or
		// something went wrong.  Therefore, return the original URL without modification
		if ((substr ($url, 0, 2) != "~1") && (substr ($url, strlen ($url) - 2, 2) != "~2"))
			return ($url);

		// replace '?'
		$url = str_replace ("~3", "?", $url);

		// replace '&'
		$url = str_replace ("~4", "&", $url);

		// remove start and end tokens
		$url = str_replace ("~1", "", $url);
		$url = str_replace ("~2", "", $url);
		return ($url);
	}
		
	// Function used to protect the access to a web page.
	// Usage is similar to the isEnabled function
	function pageProtect($usersArray,$groupsArray) {
		require "phpauthent_config.php";
		if (!isEnabled($usersArray,$groupsArray)) {
			// Redirects to the login page
			$current_location = $_SERVER['SCRIPT_NAME'];
			if ($_SERVER['QUERY_STRING'] != "")
				$current_location .= "?" . $_SERVER['QUERY_STRING'];
			$current_location = encodeURL ($current_location);
			if (isUserLogged()) {
				// Definitely not enough access rights to access this page
				header ("Location: ".$phpauth_login."?refurl=".$current_location."&err=019");
			} else {
				// A logged user access is required
				header ("Location: ".$phpauth_login."?refurl=".$current_location."&err=012");
			}
		}
	}
	
	// Simple page protection that requires a valid user connected to be accessed
	function simplePageProtect() {
		require "phpauthent_config.php";
		if (!isUserLogged()) {
			// Redirects to the login page
			$current_location = $_SERVER['SCRIPT_NAME'];
			if ($_SERVER['QUERY_STRING'] != "")
				$current_location .= "?" . $_SERVER['QUERY_STRING'];
			$current_location = encodeURL ($current_location);
			header ("Location: ".$phpauth_login."?refurl=".$current_location."&err=012");
		}
	}
	
	function getLoginPath() {
		require "phpauthent_config.php";
		return $phpauth_login;
	}
	
	function getLogoutPath() {
		require "phpauthent_config.php";
		return $phpauth_logout;
	}
		
	// ------------------------------------------------------------------------
	// Encryption functions
	// ------------------------------------------------------------------------
	
	// Function that converts a string to hexadecimal
	function asc2hex ($temp) {
	   $data = "";
	   $len = strlen($temp);
	   for ($i=0; $i<$len; $i++) $data.=sprintf("%02x",ord(substr($temp,$i,1)));
	   return $data;
	}
	
	// Function that convert an hexadecimal string to ascii
	function hex2asc($temp) {
	   $data = "";
	   $len = strlen($temp);
	   for ($i=0;$i<$len;$i+=2) $data.=chr(hexdec(substr($temp,$i,2)));
	   return $data;
	}

	// String encryption function
	function encrypt($string, $key) {
		$result = '';
		for($i=1; $i<=strlen($string); $i++) {
			$char = substr($string, $i-1, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return asc2hex($result);
	}

	// String decryption function
	function decrypt($string, $key) {
		$result = '';
		$string = hex2asc($string);
		for($i=1; $i<=strlen($string); $i++) {
			$char = substr($string, $i-1, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
	}
	
	// ------------------------------------------------------------------------
	// Password wizard related functions
	// 
	// The functions generatePassword() and make_seed() are taken from 
	// Xantus Webdesign copyrighted password.php script.
	// ------------------------------------------------------------------------
	function generatePassword ($length) {
		$possible = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRESTUVWXYZ_"; // allowed chars in the password
		 if ($length == "" OR !is_numeric($length)){
		  $length = 8; 
		 }
	 
		 srand(make_seed());
		
		 $i = 0; 
		 $password = "";    
		 while ($i < $length) { 
		  $char = substr($possible, rand(0, strlen($possible)-1), 1);
		  if (!strstr($password, $char)) { 
		   $password .= $char;
		   $i++;
		   }
		  }
		 return $password;
	}
	
	function make_seed() {
	 list($usec, $sec) = explode(' ', microtime());
	 return (float) $sec + ((float) $usec * 100000);
	}
	
	// ------------------------------------------------------------------------
	// Default actions to be done after include
	// ------------------------------------------------------------------------
	// Sets the session lifetime - IN DEVELOPMENT - DISABLED
	//if (!empty($session_lifetime)) {
	//	session_set_cookie_params($session_lifetime);
	//}
		
	// ------------------------------------------------------------------------
	// Event driven actions
	// ------------------------------------------------------------------------
	// Processes login event
	if ((!empty($_GET['action'])) && ($_GET['action'] == "login")) {
		require "phpauthent_config.php";
		if (registerUser($_POST[$phpauth_loginform_username],$_POST[$phpauth_loginform_password]) == true) {
			if ((isset($_GET['refurl'])) && ($_GET['refurl']<>'')) {
				// Redirect to the referring page
				$newRefURL = decodeURL ($_GET['refurl']);
				// header ("Location: ".$_GET['refurl']);
				header ("Location: ".$newRefURL);
			} else {
				// Redirect to the default login_target page
				header ("Location: ".$phpauth_successfull_login_target);
			}
		} else {
			header ("Location: ".$phpauth_login."?err=011");
		}
	}
	
	// Processes logout event
	if ((!empty($_GET['action'])) && ($_GET['action'] == "logout")) {
		require "phpauthent_config.php";
		unregisterUser();
		header ("Location:".$phpauth_successfull_logout_target);
	}
?>
