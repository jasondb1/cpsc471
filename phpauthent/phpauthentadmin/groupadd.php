<?php
// $Author: vincentarn $
// $Date: 2005/04/19 21:19:36 $
// $Id: groupadd.php,v 1.11 2005/04/19 21:19:36 vincentarn Exp $
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
	//pageProtect($usersArray,$groupsArray);		

	if ((!empty($_POST['action'])) && ($_POST['action'] == "create")) {
		if ($demo_mode_enabled) {
			header ("Location: groupadd.php?msg=013");
			exit;
		}
		$p_name = $_POST['name'];
		if (empty($p_name)) {
			header ("Location: groupadd.php?err=003");
		}
		$p_description = $_POST['description'];
		$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		$query = "INSERT INTO ".$db_tbl_groups." (".$db_fld_groups_name.", ".$db_fld_groups_description." ) VALUES ('".$p_name."', '".$p_description."')";
		$r_query = $mysql_link->query($query);
		if ($mysql_link->affected_rows <> 1) {
			header ("Location: groupadd.php?err=004");
		} else {
			// Insert successful
			header ("Location: index.php?msg=002");
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
.style1 {font-size: small}
.style4 {color: #666666}
.style3 {font-size: small; font-weight: bold; }
-->
</style>
</head>

<body>
<table width="100%" border="0" cellpadding="0">
  <tr>
    <td valign="top" class="titleapp">phpAuthent<span class="style4">Admin</span></td>
  </tr>
  <tr>
    <td valign="bottom" class="titlepage"><?php echo $page_title_group_add;?></td>
  </tr>
  <tr>
    <td valign="top" class="headmenu"><a href="index.php"><?php echo $menu_link_overview;?></a> - <a href="<?php echo $phpauth_successfull_login_target;?>"><?php echo $menu_link_homepage;?></a> - <a href="../phpauthent_core.php?action=logout" class="style1"></a><a href="../phpauthent_core.php?action=logout" class="style1"><?php echo $menu_link_logout;?> (<?php echo getUsername()?>)</a></td>
  </tr>
</table>
<p class="style1"><?php echo $txt_groupadd_pageintro?></p>
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
<form name="groupadd" method="post" action="groupadd.php">
  <input type="hidden" name="action" value="create">
  <table border="0" cellpadding="2">
    <tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?php echo $txt_groupadd_field_name;?></span></td>
      <td><input name="name" type="text" id="name" maxlength="32"></td>
    </tr>
    <tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?php echo $txt_groupadd_field_desc;?></span></td>
      <td><input name="description" type="text" id="description" size="40" maxlength="80"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="<?php echo $txt_groupadd_button_create;?>"></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<p></p>
<p class="textcopy"><?php echo $phpauth_version;?></p>

</body>
</html>
