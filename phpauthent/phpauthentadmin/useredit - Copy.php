<?php
// $Author: vincentarn $
// $Date: 2005/04/19 21:19:37 $
// $Id: useredit.php,v 1.11 2005/04/19 21:19:37 vincentarn Exp $
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
	require_once("../phpauthent_core.php");
	require_once("../phpauthent_config.php");
	require_once("locale/".$phpauth_language);

	$usersArray  = array();
	$groupsArray = array("admin");
	pageProtect($usersArray,$groupsArray);		

	if ((!empty($_GET['action'])) && ($_GET['action'] == "delete")) {
		if (!empty($_GET['id'])) {
			// Security error - Id should be passed through
			$g_id = $_GET['id'];
		}
		if ($demo_mode_enabled) {
			header ("Location: useredit.php?msg=009&id=".$_GET['id']);
			exit;
		}
		$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
		mysql_select_db($db_database);
		// Do the necessary to prevent deleting a user who is the only administrator left
		$admin_group = array("admin");
		if (isUserInGroup($g_id,$admin_group)) {
			// Count the number of users in admin group. If 1, do not delete this user !
			$query = "SELECT ".$db_fld_groups_id." FROM ".$db_tbl_groups." WHERE ".$db_fld_groups_name."='admin'";
			$r_query_name = mysql_query($query);
			$row_query_name = mysql_fetch_array($r_query_name);
			$query = "SELECT COUNT(".$db_fld_relation_id.") FROM ".$db_tbl_relation." WHERE ".$db_fld_relation_gid."='".$row_query_name[$db_fld_groups_id]."'";
			$r_query_count = mysql_query($query);
			$row_query_count = mysql_fetch_array($r_query_count);
			if ($row_query_count[0] == 1) {
				// Do not delete this user, it's the only one from the admin group
				header ("Location: index.php?err=016");
				exit;
			}
		}
		
		// Prevents deleting the user we're connected with
		if (getUserId() == $g_id) {
			header ("Location: index.php?err=017");
			exit;
		}
		
		$query = "DELETE FROM ".$db_tbl_users." WHERE ".$db_fld_users_id."=".$_GET['id'];
		$r_query = mysql_query($query);
		if (mysql_affected_rows() <> 1) {
			header ("Location: useredit.php?err=005");
		} else {
			// Delete successful
			// Now deleting old relations
			$query = "DELETE FROM ".$db_tbl_relation." WHERE ".$db_fld_relation_uid."=".$_GET['id'];
			$r_query = mysql_query($query);
			header ("Location: index.php?msg=003");
		} 
	} else if (!empty($_POST['action'])) {
			if ($_POST['action'] == "rename") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=015&id=".$_GET['id']);
				exit;
			}
			$p_username = $_POST['username'];
			$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			mysql_select_db($db_database);
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_username."='".$p_username."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = mysql_query($query);
			if (mysql_affected_rows() <> 1) {
				header ("Location: useredit.php?err=009&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=007&id=".$_GET['id']);
			} 
		}  else if ($_POST['action'] == "changerealname") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=020&id=".$_GET['id']);
				exit;
			}
			$p_realname = $_POST['realname'];
			$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			mysql_select_db($db_database);
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_realname."='".$p_realname."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = mysql_query($query);
			if (mysql_affected_rows() <> 1) {
				header ("Location: useredit.php?err=013&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=017&id=".$_GET['id']);
			} 
//////////////////////////////////////////			
			}  else if ($_POST['action'] == "changetitle") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=020&id=".$_GET['id']);
				exit;
			}
			$p_title = $_POST['title'];
			$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			mysql_select_db($db_database);
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_title."='".$p_title."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = mysql_query($query);
			if (mysql_affected_rows() <> 1) {
				header ("Location: useredit.php?err=013&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=017&id=".$_GET['id']);
			} 
					}  else if ($_POST['action'] == "changeactive") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=020&id=".$_GET['id']);
				exit;
			}
			$p_status = $_POST['status'];
			$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			mysql_select_db($db_database);
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_status."='".$p_status."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = mysql_query($query);
			if (mysql_affected_rows() <> 1) {
				header ("Location: useredit.php?err=013&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=017&id=".$_GET['id']);
			} 
					}  else if ($_POST['action'] == "changephone") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=020&id=".$_GET['id']);
				exit;
			}
			$p_phone = $_POST['phone'];
			$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			mysql_select_db($db_database);
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_phone."='".$p_phone."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = mysql_query($query);
			if (mysql_affected_rows() <> 1) {
				header ("Location: useredit.php?err=013&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=017&id=".$_GET['id']);
			} 
					}  else if ($_POST['action'] == "changestreet") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=020&id=".$_GET['id']);
				exit;
			}
			$p_street = $_POST['street'];
			$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			mysql_select_db($db_database);
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_street."='".$p_street."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = mysql_query($query);
			if (mysql_affected_rows() <> 1) {
				header ("Location: useredit.php?err=013&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=017&id=".$_GET['id']);
			} 
					}  else if ($_POST['action'] == "changecity") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=020&id=".$_GET['id']);
				exit;
			}
			$p_city = $_POST['city'];
			$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			mysql_select_db($db_database);
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_city."='".$p_city."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = mysql_query($query);
			if (mysql_affected_rows() <> 1) {
				header ("Location: useredit.php?err=013&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=017&id=".$_GET['id']);
			} 
					}  else if ($_POST['action'] == "changeprov") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=020&id=".$_GET['id']);
				exit;
			}
			$p_prov = $_POST['prov'];
			$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			mysql_select_db($db_database);
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_prov."='".$p_prov."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = mysql_query($query);
			if (mysql_affected_rows() <> 1) {
				header ("Location: useredit.php?err=013&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=017&id=".$_GET['id']);
			} 
					}  else if ($_POST['action'] == "changepostal") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=020&id=".$_GET['id']);
				exit;
			}
			$p_postal = $_POST['postal'];
			$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			mysql_select_db($db_database);
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_postal."='".$p_postal."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = mysql_query($query);
			if (mysql_affected_rows() <> 1) {
				header ("Location: useredit.php?err=013&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=017&id=".$_GET['id']);
			} 
					}  else if ($_POST['action'] == "changedob") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=020&id=".$_GET['id']);
				exit;
			}
			$p_dob = $_POST['dob'];
			$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			mysql_select_db($db_database);
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_dob."='".$p_dob."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = mysql_query($query);
			if (mysql_affected_rows() <> 1) {
				header ("Location: useredit.php?err=013&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=017&id=".$_GET['id']);
			} 
					}  else if ($_POST['action'] == "changestart") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=020&id=".$_GET['id']);
				exit;
			}
			$p_start = $_POST['start'];
			$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			mysql_select_db($db_database);
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_start."='".$p_start."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = mysql_query($query);
			if (mysql_affected_rows() <> 1) {
				header ("Location: useredit.php?err=013&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=017&id=".$_GET['id']);
			} 
			
//////////////////////////////////			
		} else if ($_POST['action'] == "password") {
			// Change password
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=016&id=".$_GET['id']);
				exit;
			}
			$p_newpass = $_POST['newpass'];
			$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			mysql_select_db($db_database);
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_password."='".encrypt($p_newpass,$phpauthent_enckey)."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = mysql_query($query);
			if (mysql_affected_rows() <> 1) {
				header ("Location: useredit.php?err=010&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=008&id=".$_GET['id']);
			} 
		} else if ($_POST['action'] == 'changeemail') {
			// Change email
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=021&id=".$_GET['id']);
				exit;
			}
			$p_newemail = $_POST['email'];
			$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
			mysql_select_db($db_database);
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_email."='".$p_newemail."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = mysql_query($query);
			if (mysql_affected_rows() <> 1) {
				header ("Location: useredit.php?err=015&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=022&id=".$_GET['id']);
			}
		}
	}
	// Retrieve user information from database
	$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
	mysql_select_db($db_database);
	$query = "SELECT * FROM ".$db_tbl_users." WHERE ".$db_fld_users_id."=".$_GET['id'];
	$r_query = mysql_query($query);
	while ($row = mysql_fetch_array($r_query)) {
		$user_username 		= $row[$db_fld_users_username];
		$user_password 		= $row[$db_fld_users_password];
		$user_realname 		= $row[$db_fld_users_realname];
		$user_emailadr 		= $row[$db_fld_users_email];
		$user_creation_date = $row[$db_fld_users_creationdate];
		$user_lastlogin_date= $row[$db_fld_users_lastlogin];
		$user_nbconnections = $row[$db_fld_users_connections];
		
		$user_title 		= $row[$db_fld_users_title];
		$user_phone 		= $row[$db_fld_users_phone];
		$user_status 		= $row[$db_fld_users_status];
		$user_street 		= $row[$db_fld_users_street];
		$user_city 			= $row[$db_fld_users_city];
		$user_prov 			= $row[$db_fld_users_prov];
		$user_postal 		= $row[$db_fld_users_postal];
		$user_dob 			= $row[$db_fld_users_dob];
		$user_start 		= $row[$db_fld_users_start];
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
</head>

<body>
<table width="100%" border="0" cellpadding="0">
  <tr>
    <td valign="top" class="titleapp">phpAuthent<span class="style5">Admin</span></td>
  </tr>
  <tr>
    <td valign="bottom" class="titlepage"><?=$page_title_user_edit?> <em>
    <?=$user_username?>
</em></td>
  </tr>
  <tr>
    <td valign="top" class="headmenu"><a href="index.php"><?=$menu_link_overview?></a> - <a href="<?=$phpauth_successfull_login_target?>"><?=$menu_link_homepage?></a> - <a href="../phpauthent_core.php?action=logout" class="style1"></a> <a href="../phpauthent_core.php?action=logout"><?=$menu_link_logout?> (<?=getUsername()?>)</a></td>
  </tr>
</table>
<p class="style1"><?=$txt_useredit_pageintro?></p>
<table border="0" cellpadding="2">
  <tr>
    <td colspan="4" class="titlepage"><?=$txt_useredit_details_title?></td>
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
  <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="changerealname"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useredit_field_name?>*</span></td>
    <td><?=$user_realname?></td>
    <td><input name="realname" type="text" id="realname" size="40" maxlength="80"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>
  </form>
  <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="rename"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useredit_field_login?>*</span></td>
    <td><?=$user_username?></span></td>
    <td><input name="username" type="text" maxlength="32"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>
  </form>
  <form name="passwordform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="password"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useredit_field_password?>*</span></td>
    <td><?=decrypt($user_password,$phpauthent_enckey)?></td>
    <td><input name="newpass" type="text" maxlength="32"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>
  </form>
  <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="changeemail"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useredit_field_email?>*</span></td>
    <td><?=$user_emailadr?></td>
    <td><input name="email" type="text" id="email" size="60" maxlength="120"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>  
  </form>
  
   <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="changetitle"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3">Title</span></td>
    <td><?=$user_title?></td>
    <td><input name="title" type="text" id="title" maxlength="30"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>  
  </form>
     <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="changeactive"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3">Status*</span></td>
    <td><?=$user_status?></td>
    <td><select name="status"><option selected="selected" id="status">Active</option><option>Inactive</option></select></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>  
  </form>
     <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="changephone"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3">Phone</span></td>
    <td><?=$user_phone?></td>
    <td><input name="phone" type="text" id="phone" maxlength="16"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>  
  </form>
     <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="changestreet"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3">Street</span></td>
    <td><?=$user_street?></td>
    <td><input name="street" type="text" id="street" size="45" maxlength="64"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>  
  </form>
     <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="changecity"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3">City</span></td>
    <td><?=$user_city?></td>
    <td><input name="city" type="text" id="city" maxlength="32"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>  
  </form>
     <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="changeprov"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3">Province</span></td>
    <td><?=$user_prov?></td>
    <td><input name="prov" type="text" id="prov" maxlength="16"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>  
  </form>
     <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="changepostal"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3">Postal Code</span></td>
    <td><?=$user_postal?></td>
    <td><input name="postal" type="text" id="postal" maxlength="16"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>  
  </form>
     <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="changedob"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3">Date of Birth</span></td>
    <td><?=$user_dob?></td>
    <td><input name="dob" type="date" id="dob" maxlength="120"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>  
  </form>
       <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="changestart"/>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3">Start Date</span></td>
    <td><?=$user_start?></td>
    <td><input name="start" type="date" id="start" maxlength="120"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>  
  </form>

  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#E9E9E9" class="style3"><?=$txt_useredit_field_creation_date?></td>
    <td><?=$user_creation_date?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useredit_field_last_login?></span></td>
    <td><?=$user_lastlogin_date?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useredit_field_connections?></span></td>
    <td><?=$user_nbconnections?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#E9E9E9"><strong class="style3"><?=$txt_useredit_field_db_id?></strong></td>
    <td><?=$_GET['id']?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p class="style1">* <?=$txt_useredit_footer_text?></p>
<p class="textcopy">
  <?=$phpauth_version?>
</p>
</body>
</html>
