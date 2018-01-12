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
	require_once("../phpauthent_core.php");
	require_once("../phpauthent_config.php");
	require_once("locale/".$phpauth_language);

	$usersArray  = array();
	$groupsArray = array("admin");
	pageProtect($usersArray,$groupsArray);		

	if ((!empty($_GET['action'])) && ($_GET['action'] == "create")) {
		if ($demo_mode_enabled) {
			header ("Location: useradd.php?msg=012");
			exit;
		}
		$p_username = $_POST['login'];
		$p_password = $_POST['password'];
		$p_realname = $_POST['name'];
		$p_email    = $_POST['email'];
		
		$p_status	= $_POST['status'];
		$p_title	= $_POST['title'];
		$p_phone	= $_POST['phone'];
		$p_street	= $_POST['street'];
		$p_city		= $_POST['city'];
		$p_prov		= $_POST['prov'];
		$p_postal	= $_POST['postal'];
		$p_start	= $_POST['start'];
		$p_dob		= $_POST['dob'];
		
		if ((empty($p_username)) || (empty($p_password)) || (empty($p_status))) {
			header ("Location: useradd.php?err=001");
		}
		
		/*
			$db_fld_users_lastlogin    = 'lastlogin';
	$db_fld_users_connections  = 'numlogins';
	$db_fld_users_creationdate = 'creation';
	*/
		$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
		mysql_select_db($db_database);
		$query = "INSERT INTO ".$db_tbl_users." (".$db_fld_users_username." , ".$db_fld_users_password." , ".$db_fld_users_realname." , ".$db_fld_users_email." , ".$db_fld_users_creationdate.
		" , ".$db_fld_users_title.
		" , ".$db_fld_users_status.
		" , ".$db_fld_users_street.
		" , ".$db_fld_users_city.
		" , ".$db_fld_users_prov.
		" , ".$db_fld_users_postal.
		" , ".$db_fld_users_phone.
		" , ".$db_fld_users_start.
		" , ".$db_fld_users_dob.
		") VALUES ('".$p_username."', '".encrypt($p_password,$phpauthent_enckey)."', '".$p_realname."', '".$p_email."', '".date("Y-m-d").
		"', '".$p_title.
		"', '".$p_status.
		"', '".$p_street.
		"', '".$p_city.
		"', '".$p_prov.
		"', '".$p_postal.
		"', '".$p_phone.
		"', '".$p_start.
		"', '".$p_dob.
		"'		)";
		//DEBUG 
		//echo "query : <b>".$query."</b>";
		$r_query = mysql_query($query);
		if (mysql_affected_rows() <> 1) {
			header ("Location: useradd.php?err=002");
		} else {
			// Insert successful
			header ("Location: index.php?msg=001");
		} 
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
.style2 {font-size: small}
.style3 {font-size: small; font-weight: bold; }
.style5 {color: #666666}
-->
</style></head>

<body>
<table width="100%" border="0" cellpadding="0">
  <tr>
    <td valign="top" class="titleapp">phpAuthent<span class="style5">Admin</span></td>
  </tr>
  <tr>
    <td valign="bottom" class="titlepage"><?=$page_title_user_add?></td>
  </tr>
  <tr>
    <td valign="top" class="headmenu"><a href="index.php"><?=$menu_link_overview?></a> - <a href="<?=$phpauth_successfull_login_target?>"><?=$menu_link_homepage?></a> - <a href="../phpauthent_core.php?action=logout" class="style2"></a><a href="../phpauthent_core.php?action=logout"><?=$menu_link_logout?> (<?=getUsername()?>)</a></td>
  </tr>
</table>
<p class="style2"><?=$txt_useradd_pageintro?></p>
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
    <input name="action" type="hidden" value="changerealname"/>
    <tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useradd_field_name?></span></td>
      <td><input name="name" type="text" id="name" size="40" maxlength="80"></td>
    </tr>
    <input name="action" type="hidden" value="rename"/>
    <tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useradd_field_login?></span></td>
      <td><input name="login" type="text" id="login" maxlength="32"></td>
    </tr>
    <input name="action" type="hidden" value="password"/>
    <tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useradd_field_password?></span></td>
      <td><input name="password" type="password" id="password" maxlength="32"></td>
    </tr>
    <input name="action" type="hidden" value="changeemail"/>
    <tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useradd_field_email?></span></td>
      <td><input name="email" type="text" id="email" size="60" maxlength="120"></td>
    </tr>
	
	<tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useradd_field_title?></span></td>
      <td><input name="title" type="text" id="title" maxlength="30"></td>
    </tr>
	<tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useradd_field_phone?></span></td>
      <td><input name="phone" type="text" id="phone" maxlength="20"></td>
    </tr>
	<tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useradd_field_status?></span></td>
      <td><select name="status"><option selected="selected" id="status">Active</option><option>Inactive</option></select></td>
    </tr>
	<tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useradd_field_street?></span></td>
      <td><input name="street" type="text" id="street" size="60" maxlength="64"></td>
    </tr>
	<tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useradd_field_city?></span></td>
      <td><input name="city" type="text" id="city" maxlength="32"></td>
    </tr>
	<tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useradd_field_prov?></span></td>
      <td><input name="prov" type="text" id="prov" maxlength="16"></td>
    </tr>
	<tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useradd_field_postal?></span></td>
      <td><input name="postal" type="text" id="postal" maxlength="16"></td>
    </tr>
	<tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useradd_field_start?></span></td>
      <td><input name="start" type="text" id="start" value="<? echo date("Y-m-d"); ?>" ></td>
    </tr>
	<tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?=$txt_useradd_field_dob?></span></td>
      <td><input name="dob" type="text" id="dob" ></td>
    </tr>
		
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="<?=$txt_useradd_button_create?>"></td>
    </tr>
  </table>
</form>
<p class="textcopy">
  <?=$phpauth_version?>
</p>
</body>
</html>
