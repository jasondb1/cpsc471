<?php
// $Author: vincentarn $
// $Date: 2005/04/19 21:19:37 $
// $Id: useradd.php,v 1.11 2005/04/19 21:19:37 vincentarn Exp $
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
//myfunction inserted///////////////////////////
function getEmployeeNames(){
		require "../../_config.php";
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
	//pageProtect($usersArray,$groupsArray);		

if ((!empty($_GET['action'])) && ($_GET['action'] == "create")) {
		if ($demo_mode_enabled) {
			header ("Location: useradd.php?msg=012");
			exit;
		}
		$p_username = $_POST['login'];
		$p_password = $_POST['password'];
		$p_realname = $_POST['name'];
		$p_email    = $_POST['email'];
		if ((empty($p_username)) || (empty($p_password))) {
			header ("Location: useradd.php?err=001");
		}
		

		
		/*
			$db_fld_users_lastlogin    = 'lastlogin';
	$db_fld_users_connections  = 'numlogins';
	$db_fld_users_creationdate = 'creation';
	*/
		//$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		$query = "INSERT INTO ".$db_tbl_users." (".$db_fld_users_username." , ".$db_fld_users_password." , ".$db_fld_users_realname." , ".$db_fld_users_email." , ".$db_fld_users_creationdate.") VALUES ('".$p_username."', '".encrypt($p_password,$phpauthent_enckey)."', '".$p_realname."', '".$p_email."', '".date("Y-m-d")."')";
		//DEBUG 
		//echo "query : <b>".$query."</b>";
		$r_query =  $mysql_link->query($query);
		if ($mysql_link->affected_rows <> 1) {
			header ("Location: useradd.php?err=002");
		} else {
			// Insert successful
			//header ("Location: index.php?msg=001");
		}

//mycode additions
		$uid = $mysql_link->insert_id;
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
		
		//enter into database
		//Enter data into database
			// if($field1 =="" || $field2=="" {
			// echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
			// echo "<br><br><b><big>Hey BOZO! You forgot some information!</b></big><br>Fill in all of the fields marked with an *<br><br>";
			// echo 	'<input type="Button" value="Back" onclick="history.go(-1)">';
			// die();
			// }
			if (1){//enter constraint
			$sql = "INSERT INTO employee_info (`uid`,
			`start_date`,
			`ft_pt`,
			`hourly_salary`,
			`compensation`,
			`position`,
			`division`,
			`pay_increase_date`,
			`sin`,
			`dob`,
			`td1`,
			`td1ab`,
			`home_phone`,
			`home_cell`,
			`home_email`,
			`street`,
			`city`,
			`province`,
			`postal_code`,
			`work_email`,
			`work_phone`,
			`work_cell`,
			`drivers_license`,
			`expiry`,
			`emergency_contact`,
			`emerg_number`,
			`notes`,
			`status`,
			`supervisor`)
			VALUES (
			'$uid',
			'$start_date',
			'$ft_pt',
			'$hourly_salary',
			'$compensation',
			'$position',
			'$division',
			'$pay_increase_date',
			'$sin',
			'$dob',
			'$td1',
			'$td1ab',
			'$home_phone',
			'$home_cell',
			'$home_email',
			'$street',
			'$city',
			'$province',
			'$postal_code',
			'$work_email',
			'$work_phone',
			'$work_cell',
			'$drivers_license',
			'$expiry',
			'$emergency_contact',
			'$emerg_number',
			'$notes',
			'$status',
			'$supervisor')";
			}
			else{
			$sql = "UPDATE employee_info SET
			`uid`  =  '$uid',
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
			`supervisor`  =  '$supervisor' WHERE 1
			";}//end else

			$retval = $mysql_link->query($sql);
			
			
		
	}
	$employee_list = getEmployeeNames();//get employee names
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>phpAuthent Administration</title>
<link href="css/phpauth.css" rel="stylesheet" type="text/css">
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
    <td valign="bottom" class="titlepage"><?php echo $page_title_user_add?></td>
  </tr>
  <tr>
    <td valign="top" class="headmenu"><a href="index.php"><?php echo $menu_link_overview?></a> - <a href="<?php echo $phpauth_successfull_login_target?>"><?php echo $menu_link_homepage?></a> - <a href="../phpauthent_core.php?action=logout" class="style2"></a><a href="../phpauthent_core.php?action=logout"><?php echo $menu_link_logout?> (<?php echo getUsername()?>)</a></td>
  </tr>
</table>
<p class="style2"><?php echo $txt_useradd_pageintro;?></p>
<p>
  <?php
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
?>
</p>
<form name="useradd" method="post" action="useradd.php?action=create">
  <table border="0" cellpadding="2">
    <input class="text" name="action" type="hidden" value="changerealname" />
    <tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?php echo $txt_useradd_field_name;?></span></td>
      <td><input class="text" name="name" type="text" id="name" size="40" maxlength="80"></td>
    </tr>
    <input class="text" name="action" type="hidden" value="rename" />
    <tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?php echo $txt_useradd_field_login;?></span></td>
      <td><input class="text" name="login" type="text" id="login" maxlength="32"></td>
    </tr>
    <input class="text" name="action" type="hidden" value="password" />
    <tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?php echo $txt_useradd_field_password;?></span></td>
      <td><input class="text" name="password" type="text" id="password" maxlength="32"></td>
    </tr>
    <input class="text" name="action" type="hidden" value="changeemail" />
    <tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?php echo $txt_useradd_field_email;?></span></td>
      <td><input class="text" name="email" type="text" id="email" size="60" maxlength="120" ></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="<?php echo $txt_useradd_button_create;?>"></td>
    </tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Status</span></td>
<td>
             <select name="status">
                <?php
			  	// ACTIVE / INACTIVE
				echo "<option value=\"Active\">Active</option>";
				echo "<option value=\"Inactive\">Inactive</option>";            
			  ?>
              </select>
</td>
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
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Start Date</span></td>
<td><input class="text" id="datepicker1" name="start_date" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Full Time/Part Time</span></td>
<td>
             <select name="ft_pt">
                <?
			  	// ACTIVE / INACTIVE
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
                <?php
			  	// ACTIVE / INACTIVE
				echo "<option value=\"Hourly\">Hourly</option>";
				echo "<option value=\"Salary\">Salary</option>";            
			  ?>
              </select>
</td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Compensation</span></td>
<td><input class="text" id="compensation" name="compensation" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Position</span></td>
<td><input class="text" id="position" name="position" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Division</span></td>
<td><input class="text" id="division" name="division" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Pay Increase Date</span></td>
<td><input class="text" id="datepicker2" name="pay_increase_date" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">SIN</span></td>
<td><input class="text" id="sin" name="sin" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Date of Birth</span></td>
<td><input class="text" id="datepicker3" name="dob" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">td1</span></td>
<td><input class="text" id="td1" name="td1" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">td1ab</span></td>
<td><input class="text" id="td1ab" name="td1ab" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Home Phone</span></td>
<td><input class="text" id="home_phone" name="home_phone" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Home Cell</span></td>
<td><input class="text" id="home_cell" name="home_cell" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Home Email</span></td>
<td><input class="text" id="home_email" name="home_email" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Street</span></td>
<td><input class="text" id="street" name="street" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">City</span></td>
<td><input class="text" id="city" name="city" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Province</span></td>
<td><input class="text" id="province" name="province" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Postal Code</span></td>
<td><input class="text" id="postal_code" name="postal_code" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Work Email</span></td>
<td><input class="text" id="work_email" name="work_email" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Work Phone</span></td>
<td><input class="text" id="work_phone" name="work_phone" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Work Cell</span></td>
<td><input class="text" id="work_cell" name="work_cell" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Drivers License</span></td>
<td><input class="text" id="drivers_license" name="drivers_license" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">DL Expiry</span></td>
<td><input class="text" id="datepicker4" name="expiry" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Emerg Contact</span></td>
<td><input class="text" id="emergency_contact" name="emergency_contact" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Emerg Phone</span></td>
<td><input class="text" id="emerg_number" name="emerg_number" type="text" value='' /></td>
</tr>
<tr>
<td bgcolor="#E9E9E9"><span class="style3">Notes</span></td>
<td><input class="text" id="notes" name="notes" type="text" value='' /></td>
</tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="<?php echo $txt_useradd_button_create?>"></td>
    </tr>
  </table>
</form>
<p class="textcopy">
  <?php echo $phpauth_version;?>
</p>
</body>
</html>
