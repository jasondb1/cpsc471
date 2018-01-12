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

//myfunction inserted///////////////////////////
function getEmployeeNames(){
		require "../../_config.php";
		//$mysql_link = mysql_connect($dbhost,$dbusername,$dbpass) or die("Failed to connect to MySQL");
		//mysql_select_db($dbname);
		//$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		//$sql = "SELECT username FROM $db_employee WHERE status='active'";
		$sql = "SELECT username FROM $db_employee JOIN employee_info ON `$db_employee`.id = employee_info.uid WHERE employee_info.status='Active'";
		$retval = $mysql_link->query($sql);
		while ($row = $retval->fetch_assoc()) {
			$employee_list[]=$row['username'];
		}
		sort ($employee_list);
		return $employee_list;
}
////////////////////////////////////////////////

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
		$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		// Do the necessary to prevent deleting a user who is the only administrator left
		$admin_group = array("admin");
		if (isUserInGroup($g_id,$admin_group)) {
			// Count the number of users in admin group. If 1, do not delete this user !
			$query = "SELECT ".$db_fld_groups_id." FROM ".$db_tbl_groups." WHERE ".$db_fld_groups_name."='admin'";
			$r_query_name = $mysql_link->query($query);
			$row_query_name = $r_query_name->fetch_assoc();
			$query = "SELECT COUNT(".$db_fld_relation_id.") FROM ".$db_tbl_relation." WHERE ".$db_fld_relation_gid."='".$row_query_name[$db_fld_groups_id]."'";
			$r_query_count = $mysql_link->query($query);
			$row_query_count = $r_query_count->fetch_assoc();
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
		$r_query = $mysql_link->query($query);
		if ($mysql_link->affected_rows <> 1) {
			header ("Location: useredit.php?err=005");
		} else {
			// Delete successful
			// Now deleting old relations
			$query = "DELETE FROM ".$db_tbl_relation." WHERE ".$db_fld_relation_uid."=".$_GET['id'];
			$r_query = $mysql_link->query($query);
			header ("Location: index.php?msg=003");
		} 
	} //end if delete
	else if (!empty($_POST['action'])) {
		if ($_POST['action'] == "rename") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=015&id=".$_GET['id']);
				exit;
			}
			$p_username = $_POST['username'];
			$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
			if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_username."='".$p_username."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = $mysql_link->query($query);
			if ($mysql_link->affected_rows <> 1) {
				header ("Location: useredit.php?err=009&id=".$_GET['id']);
			} 
			else {
				// Update successful
				header ("Location: useredit.php?msg=007&id=".$_GET['id']);
			} 
		}  
		else if ($_POST['action'] == "changerealname") {
			// Rename user
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=020&id=".$_GET['id']);
				exit;
			}
			$p_realname = $_POST['realname'];
			$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
			if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_realname."='".$p_realname."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = $mysql_link->query($query);
			if ($mysql_link->affected_rows <> 1) {
				header ("Location: useredit.php?err=013&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=017&id=".$_GET['id']);
			} 
		} 
		else if ($_POST['action'] == "password") {
			// Change password
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=016&id=".$_GET['id']);
				exit;
			}
			$p_newpass = $_POST['newpass'];
			$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
			if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_password."='".encrypt($p_newpass,$phpauthent_enckey)."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = $mysql_link->query($query);
			if ($mysql_link->affected_rows <> 1) {
				header ("Location: useredit.php?err=010&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=008&id=".$_GET['id']);
			} 
		} 
		else if ($_POST['action'] == 'changeemail') {
			// Change email
			if ($demo_mode_enabled) {
				header ("Location: useredit.php?msg=021&id=".$_GET['id']);
				exit;
			}
			$p_newemail = $_POST['email'];
			$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
			if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
			$query = "UPDATE ".$db_tbl_users." SET ".$db_fld_users_email."='".$p_newemail."' WHERE ".$db_fld_users_id."=".$_GET['id'];
			$r_query = $mysql_link->query($query);
			if ($mysql_link->affected_rows <> 1) {
				header ("Location: useredit.php?err=015&id=".$_GET['id']);
			} else {
				// Update successful
				header ("Location: useredit.php?msg=022&id=".$_GET['id']);
			}
		}
////mycode here		
		else if ($_POST['action'] == 'changeinfo'){
			$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
			if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
			
			//write code for submitting
			//$_Post variables
			$uid = $_POST['uid'];
			$start_date = $_POST['start_date'];
			$ft_pt = $_POST['ft_pt'];
			$hourly_salary = $_POST['hourly_salary'];
			$compensation = $_POST['compensation'];
			$position = $_POST['position'];
			$division = $_POST['division'];
			$pay_increase_date = $_POST['pay_increase_date'];
			$sin = $_POST['sin'];
			$dob = $_POST['dob'];
			$td1 = $_POST['td1'];
			$td1ab = $_POST['td1ab'];
			$home_phone = $_POST['home_phone'];
			$home_cell = $_POST['home_cell'];
			$home_email = $_POST['home_email'];
			$street = $_POST['street'];
			$city = $_POST['city'];
			$province = $_POST['province'];
			$postal_code = $_POST['postal_code'];
			$work_email = $_POST['work_email'];
			$work_phone = $_POST['work_phone'];
			$work_cell = $_POST['work_cell'];
			$drivers_license = $_POST['drivers_license'];
			$expiry = $_POST['expiry'];
			$emergency_contact = $_POST['emergency_contact'];
			$emerg_number = $_POST['emerg_number'];
			$notes = $_POST['notes'];
			$status = $_POST['status'];
			$supervisor = $_POST['supervisor'];

			//Data Validation
			//Write validation code below
			// if($field1 =="" || $field2=="" {
			// echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
			// echo "<br><br><b><big>Hey BOZO! You forgot some information!</b></big><br>Fill in all of the fields marked with an *<br><br>";
			// echo 	'<input type="Button" value="Back" onclick="history.go(-1)">';
			// die();
			// }

			//Enter data into database
			$uid= $_GET['id'];
			//echo "made it here";
			$sql = "UPDATE employee_info SET
			`uid` = '$uid',
			`start_date`  =  '$start_date',
			`ft_pt`  =  '$ft_pt',
			`hourly_salary`  =  '$hourly_salary',
			`compensation`  =  '$compensation',
			`position`  =  '$position',
			`division`  =  '$division',
			`pay_increase_date`  =  '$pay_increase_date',
			`sin`  =  '$sin',
			`dob`  =  '$dob',
			`td1`  =  '$td1',
			`td1ab`  =  '$td1ab',
			`home_phone`  =  '$home_phone',
			`home_cell`  =  '$home_cell',
			`home_email`  =  '$home_email',
			`street`  =  '$street',
			`city`  =  '$city',
			`province`  =  '$province',
			`postal_code`  =  '$postal_code',
			`work_email`  =  '$work_email',
			`work_phone`  =  '$work_phone',
			`work_cell`  =  '$work_cell',
			`drivers_license`  =  '$drivers_license',
			`expiry`  =  '$expiry',
			`emergency_contact`  =  '$emergency_contact',
			`emerg_number`  =  '$emerg_number',
			`notes`  =  '$notes',
			`status`  =  '$status',
			`supervisor`  =  '$supervisor' WHERE uid='$uid'
			";
			echo $sql;
			//echo "here";
			$retval = $mysql_link->query($sql);
			echo "employee_info changed";

			if ($mysql_link->affected_rows <> 1) {
							header ("Location: useredit.php?err=020&id=".$_GET['id']);
						} else {
							// Update successful
							header ("Location: useredit.php?msg=024&id=".$_GET['id']);
						}
	}//end elseif submit
		
		
////endmycode		
}//end else if
	
	
	// Retrieve user information from database
	$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
	if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
	$query = "SELECT ".$db_fld_users_username.", ".$db_fld_users_password.", ".$db_fld_users_realname.", ".$db_fld_users_email.", ".$db_fld_users_creationdate.", ".$db_fld_users_lastlogin.", ".$db_fld_users_connections." FROM ".$db_tbl_users." WHERE ".$db_fld_users_id."=".$_GET['id'];
	$r_query = $mysql_link->query($query);
	
	while ($row = $r_query->fetch_assoc()) {
		$user_username = $row[$db_fld_users_username];
		$user_password = $row[$db_fld_users_password];
		$user_realname = $row[$db_fld_users_realname];
		$user_emailadr = $row[$db_fld_users_email];
		$user_creation_date = $row[$db_fld_users_creationdate];
		$user_lastlogin_date = $row[$db_fld_users_lastlogin];
		$user_nbconnections = $row[$db_fld_users_connections];
	}
	
	$sql= "SELECT * FROM employee_info WHERE uid='". $_GET['id']   ."'";
	$retval = $mysql_link->query($sql);
	$row = $retval->fetch_assoc();
	
	$status=$row['status'];
	$supervisor=$row['supervisor'];
	
	$employee_list = getEmployeeNames();//get employee names
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
<script src="../../js/jquery.min.js"></script>
<link rel="stylesheet" href="../../css/jquery-ui.min.css">
		<link rel="stylesheet" href="../../css/jquery-ui.theme.min.css">
		<link rel="stylesheet" href="../../css/jquery-ui.structure.min.css">
		<link rel="stylesheet" href="../../css/jquery-ui-timepicker-addon.css">

		<script src="../../js/jquery-ui.min.js"></script>
		<script src="../../js/jquery-ui-timepicker-addon.js"></script>
<script>
			$(function() {
				$( "#datepicker1" ).datepicker(
					{
					dateFormat: "yy-mm-dd",
					showButtonPanel: true,
					changeMonth: true,
					changeYear: true,
					showWeek: true,
					firstDay: 1
					}
				);
				$( "#datepicker2" ).datepicker(
					{
					dateFormat: "yy-mm-dd",
					showButtonPanel: true,
					changeMonth: true,
					changeYear: true,
					showWeek: true,
					firstDay: 1
					}
				);
				$( "#datepicker3" ).datepicker(
					{
					dateFormat: "yy-mm-dd",
					showButtonPanel: true,
					changeMonth: true,
					changeYear: true,
					showWeek: true,
					firstDay: 1,
					yearRange: "-75:+1"
					}
				);
				$( "#datepicker4" ).datepicker(
					{
					dateFormat: "yy-mm-dd",
					showButtonPanel: true,
					changeMonth: true,
					changeYear: true,
					showWeek: true,
					firstDay: 1
					}
				);
			});
		</script>
		<style type="text/css">
		  #datepicker1, #datepicker2, #datepicker3, #datepicker4{
			background-image:url("../../images/calendar.png");
			background-position:right center;
			background-repeat:no-repeat; }
		</style>
<style type="text/css">
<!--
.style2 {font-size: small}
.style3 {font-size: small; font-weight: bold; }
.style5 {color: #666666}
-->
legend {
			 font-weight:bold;
			 padding:0.1em;
			 margin-left:1em;
			}
			fieldset {
			 border: solid 5px #e7eae8;
			 padding: 1em;
			 border-radius: 8px;
			}	
			form label
			{
				display: block;
				float:left;
				clear:left;
				text-align: right;
				width: 8em;	
			}
		
			form input.email,
			form input.text,
			form select,
			form textarea
			{
				position: relative;
				-webkit-appearance: none;
				display: block;
				border: 0;
				background: #e3daa8;
				border-radius: 0.35em;
				padding: 0.2em 1em 0.2em 1em;
				box-shadow: inset 0 0.1em 0.1em 0 rgba(0,0,0,0.05);
				border: solid 1px rgba(0,0,0,0.15);
				-moz-transition: all 0.35s ease-in-out;
				-webkit-transition: all 0.35s ease-in-out;
				-o-transition: all 0.35s ease-in-out;
				-ms-transition: all 0.35s ease-in-out;
				transition: all 0.35s ease-in-out;
				margin-top:0.7em;
			}
					
			form textarea{
			width: 100%;
			}


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
  <input name="action" type="hidden" value="changerealname" />
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useredit_field_name?>*</span></td>
    <td><?=$user_realname?></td>
    <td><input name="realname" type="text" id="realname" size="40" maxlength="80"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>
  </form>
  <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="rename" />
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useredit_field_login?>*</span></td>
    <td><?=$user_username?></span></td>
    <td><input name="username" type="text" maxlength="32"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>
  </form>
  <form name="passwordform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="password" />
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useredit_field_password?>*</span></td>
    <td><?=decrypt($user_password,$phpauthent_enckey)?></td>
    <td><input name="newpass" type="text" maxlength="32"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>
  </form>
  <form name="renameform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="changeemail" />
  <tr>
    <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useredit_field_email?>*</span></td>
    <td><?=$user_emailadr?></td>
    <td><input name="email" type="text" id="email" size="60" maxlength="120"></td>
    <td><input type="submit" name="Submit" value="<?=$txt_useredit_button_apply_changes?>"></td>
  </tr>
  </form>
  
  <!-- mycode added -->
  <form name="infoform" method="post" action="useredit.php?id=<?=$_GET['id']?>">
  <input name="action" type="hidden" value="changeinfo" />
  
    <tr>
    <td><input type="submit" name="Submit" value="Change Other Info"></td>
  </tr>
  
  <tr>
<td bgcolor="#E9E9E9"><span class="style3">uid</span></td>
<td><input id="uid" name="uid" type="hidden" value="<?php echo $row['uid'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Status</span></td>
<td>
             <select name="status">
                <?
			  	// ACTIVE / INACTIVE
				echo "<option>".$status."</option>";
				echo "<option value=\"Active\">Active</option>";
				echo "<option value=\"Inactive\">Inactive</option>";            
			  ?>
              </select>
</td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Supervisor</span></td>
<td><select name="supervisor">
	<?php
		echo '<option>'.$supervisor.'</option>';
		foreach ($employee_list as $key=>$value){
		echo '<option>';
		echo $value;
		echo '</option>';
		}
	?>
	</select>
</td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Start Date</span></td>
<td><input class="text" id="datepicker1" name="start_date" type="text" value="<?php echo $row['start_date'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Full Time/Part Time</span></td>
<td>
             <select name="ft_pt">
                <?
			  	// ACTIVE / INACTIVE
				echo "<option value=\"". $row['ft_pt']    ."\">". $row['ft_pt']   ."</option>";
				echo "<option value=\"Full Time\">Full Time</option>";
				echo "<option value=\"Part Time\">Part Time</option>";            
			  ?>
              </select>
</td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Hourly/Salary</span></td>
<td>
             <select name="hourly_salary">
                <?
			  	// ACTIVE / INACTIVE
				echo "<option value=\"". $row['hourly_salary']    ."\">". $row['hourly_salary']   ."</option>";
				echo "<option value=\"Hourly\">Hourly</option>";
				echo "<option value=\"Salary\">Salary</option>";            
			  ?>
              </select>
</td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Compensation</span></td>
<td><input class="text" id="compensation" name="compensation" type="text" value="<?php echo $row['compensation'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Position</span></td>
<td><input class="text" id="position" name="position" type="text" value="<?php echo $row['position'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Division</span></td>
<td><input class="text" id="division" name="division" type="text" value="<?php echo $row['division'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Pay Increase Date</span></td>
<td><input class="text" id="datepicker2" name="pay_increase_date" type="text" value="<?php echo $row['pay_increase_date'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">SIN</span></td>
<td><input class="text" id="sin" name="sin" type="text" value="<?php echo $row['sin'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Date of Birth</span></td>
<td><input class="text" id="datepicker3" name="dob" type="text" value="<?php echo $row['dob'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">td1</span></td>
<td><input class="text" id="td1" name="td1" type="text" value="<?php echo $row['td1'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">td1ab</span></td>
<td><input class="text" id="td1ab" name="td1ab" type="text" value="<?php echo $row['td1ab'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Home Phone</span></td>
<td><input class="text" id="home_phone" name="home_phone" type="text" value="<?php echo $row['home_phone'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Home Cell</span></td>
<td><input class="text" id="home_cell" name="home_cell" type="text" value="<?php echo $row['home_cell'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Home Email</span></td>
<td><input class="text" id="home_email" name="home_email" type="text" value="<?php echo $row['home_email'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Street</span></td>
<td><input class="text" id="street" name="street" type="text" value="<?php echo $row['street'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">City</span></td>
<td><input class="text" id="city" name="city" type="text" value="<?php echo $row['city'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Province</span></td>
<td><input class="text" id="province" name="province" type="text" value="<?php echo $row['province'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Postal Code</span></td>
<td><input class="text" id="postal_code" name="postal_code" type="text" value="<?php echo $row['postal_code'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Work Email</span></td>
<td><input class="text" id="work_email" name="work_email" type="text" value="<?php echo $row['work_email'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Work Phone</span></td>
<td><input class="text" id="work_phone" name="work_phone" type="text" value="<?php echo $row['work_phone'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Work Cell</span></td>
<td><input class="text" id="work_cell" name="work_cell" type="text" value="<?php echo $row['work_cell'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Drivers License</span></td>
<td><input class="text" id="drivers_license" name="drivers_license" type="text" value="<?php echo $row['drivers_license'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">DL Expiry</span></td>
<td><input class="text" id="datepicker4" name="expiry" type="text" value="<?php echo $row['expiry'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Emerg Contact</span></td>
<td><input class="text" id="emergency_contact" name="emergency_contact" type="text" value="<?php echo $row['emergency_contact'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Emerg Phone</span></td>
<td><input class="text" id="emerg_number" name="emerg_number" type="text" value="<?php echo $row['emerg_phone'];?>" /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Notes</span></td>
<td><input class="text" id="notes" name="notes" type="text" value="<?php echo $row['notes'];?>" /></td>
</tr>

    <tr>
    <td><input type="submit" name="Submit" value="Change Other Info"></td>
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
