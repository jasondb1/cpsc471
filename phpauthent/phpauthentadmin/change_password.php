<?php
// $Author: vincentarn $
// $Date: 2005/04/19 21:19:37 $
// $Id: change_password.php,v 1.11 2005/04/19 21:19:37 vincentarn Exp $
// $Revision: 1.11 $

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
//ini_set('display_errors',0); 
//error_reporting(0);
	require_once("../phpauthent_core.php");
	require_once("../phpauthent_config.php");
	require_once("locale/".$phpauth_language);
	simplePageProtect();

		$id=getUserId();
	
		if (!empty($_POST['action'])) {
			if ($_POST['action'] == "rename") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: change_password.php?msg=015&id=".$id);
				exit;
			}
			$p_username = $_POST['username'];
			//$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			//mysql_select_db($db_database);
			$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
			if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_username."='".$p_username."' WHERE ".$db_fld_users_id."=".$id;
			$r_query = $mysql_link->query($query);
			if ($mysql_link->affected_rows <> 1) {
				header ("Location: change_password.php?err=009&id=".$id);
			} else {
				// Update successful
				header ("Location: change_password.php?msg=007&id=".$id);
			} 
		  
			
//////////////////////////////////			
		} else if ($_POST['action'] == "password") {
			// Change password
			if ($demo_mode_enabled) {
				header ("Location: change_password.php?msg=016&id=".$id);
				exit;
			}
			$p_newpass = $_POST['newpass'];
			$p_conf_newpass = $_POST['conf_newpass'];
			
			if( $p_newpass != $p_conf_newpass){
				header ("Location: change_password.php?msg=022&id=".$id);
			}
			
			//$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			//mysql_select_db($db_database);
			$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
			if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_password."='".encrypt($p_newpass,$phpauthent_enckey)."' WHERE ".$db_fld_users_id."=".$id;
			
			//echo $query;
			//die();
			$r_query = $mysql_link->query($query);
			if ($mysql_link->affected_rows <> 1) {
				header ("Location: change_password.php?err=010&id=".$id);
			} else {
				// Update successful
				echo "Successfully Changed Password";
				header ("Location: change_password.php?msg=008&id=".$id);
			} 
		} else if ($_POST['action'] == 'changeemail') {
			// Change email
			if ($demo_mode_enabled) {
				header ("Location: change_password.php?msg=021&id=".$id);
				exit;
			}
			$p_newemail = $_POST['email'];
			$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
			if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_email."='".$p_newemail."' WHERE ".$db_fld_users_id."=".$id;
			$r_query = $mysql_link->query($query);
			if ($mysql_link->affected_rows <> 1) {
				header ("Location: change_password.php?err=015&id=".$id);
			} else {
				// Update successful
				header ("Location: change_password.php?msg=022&id=".$id);
			}
		}
	}
	// Retrieve user information from database
	$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
	$query = "SELECT * FROM ".$db_tbl_users." WHERE ".$db_fld_users_id."=".$id;
	$r_query = $mysql_link->query($query);
	while ($row = $r_query->fetch_assoc()) {
		$user_username 		= $row[$db_fld_users_username];
		$user_password 		= $row[$db_fld_users_password];
		$user_realname 		= $row[$db_fld_users_realname];
		$user_emailadr 		= $row[$db_fld_users_email];
		$user_creation_date = $row[$db_fld_users_creationdate];
		$user_lastlogin_date= $row[$db_fld_users_lastlogin];
		$user_nbconnections = $row[$db_fld_users_connections];
		
		// $user_title 		= $row[$db_fld_users_title];
		// $user_phone 		= $row[$db_fld_users_phone];
		// $user_status 		= $row[$db_fld_users_status];
		// $user_street 		= $row[$db_fld_users_street];
		// $user_city 			= $row[$db_fld_users_city];
		// $user_prov 			= $row[$db_fld_users_prov];
		// $user_postal 		= $row[$db_fld_users_postal];
		// $user_dob 			= $row[$db_fld_users_dob];
		// $user_start 		= $row[$db_fld_users_start];
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>phpAuthent Administration</title>
<link href="css/phpauth.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {font-size: small}
.style3 {font-size: small; font-weight: bold; }
.style5 {color: #666666}
-->
</style>
<script>
function displayPass()
{
document.getElementById("complete").innerHTML="Password recorded.<br>Pass1 = " + document.getElementById("pass1").value + ".<br>Pass2 = " + document.getElementById("pass2").value + ".";
}
function checkpass()
{
document.getElementById("pass1out").innerHTML = document.getElementById("pass1").value;
document.getElementById("pass2out").innerHTML = document.getElementById("pass2").value;
if(document.getElementById("pass1").value==document.getElementById("pass2").value){
    document.getElementById("subbutton").disabled = false;
} else {
    document.getElementById("subbutton").disabled = true;
}
}
</script>

</head>

<body>
<table width="100%" border="0" cellpadding="0">
  <tr>
    <td valign="top" class="titleapp">Change Password</td>
  </tr>
  <tr>
    <td valign="bottom" class="titlepage">User <em>
    <?=$user_username?>
</em></td>
  </tr>
  <tr>
    <td valign="top" class="headmenu"><a href="/_employee_menu.php">Back</a></td>
  </tr>
</table>
<table border="0" cellpadding="2">
  <tr>
    <td colspan="4" class="titlepage">Change Password</td>
  </tr>
  <tr>
    <td colspan="4"><?php
	if (isset($_GET['err'])) {
		$err_idx = $_GET['err'];
		print "<p class=\"txtError\">".$err[$err_idx]."</p>";
	}
?>
      <?php
	if (isset($_GET['msg'])) {
		$msg_idx = $_GET['msg'];
		print "<p class=\"txtMessage\">".$msg[$msg_idx]."</p>";
	}
?></td>
  </tr>

  <form name="passwordform" method="post" action="change_password.php?id=<?=$id?>">
  <input name="action" type="hidden" value="password"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3">New Password*</span></td>

    <td><input id="pass1" name="newpass" type="password" onkeyup="checkpass()" maxlength="32"></td>
	<td style="color:#fff" id=pass1out>
	<!-- <td id=pass1out> -->
	</tr>
	<tr>
	<td bgcolor="#E9E9E9"><span class="style3">Re-Type Password*</span></td>

	<td><input id="pass2" name="conf_newpass" type="password" onkeyup="checkpass()" maxlength="32"></td>
	
    <!-- <td id=pass2out> -->
	<td style="color:#fff" id=pass2out>
	</td>
	</tr>
	<tr>
    <td><input id="subbutton" type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>" disabled ></td>
  </tr>



  
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
 
</table>
<p id="complete">&nbsp </p>
<p class="textcopy">
  <?=$phpauth_version?>
</p>
</body>
</html>
