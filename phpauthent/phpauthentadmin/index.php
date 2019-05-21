<?php
// $Author: vincentarn $
// $Date: 2005/04/08 12:54:38 $
// $Id: index.php,v 1.11 2005/04/08 12:54:38 vincentarn Exp $
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
?>
<?php
	$usersArray  = array();
	$groupsArray = array("admin");
	//pageProtect($usersArray,$groupsArray);
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
    <td valign="bottom" class="titlepage"><?php echo $page_title_overview?></td>
  </tr>
  <tr>
    <td valign="top" class="headmenu"><a href="../<?php echo $phpauth_successfull_login_target?>"><?php echo $menu_link_homepage?></a> - <a href="../phpauthent_core.php?action=logout" class="style1"><?php echo $menu_link_logout?> (<?php echo getUsername()?>)</a></td>
  </tr>
</table>
<p class="style1"><?php echo $txt_overview_pageintro?></p>
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
<h1><?php echo $txt_overview_users_title?></h1>
<?php
$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
	if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		//$query = "SELECT * FROM ".$db_tbl_users." ORDER BY ".$db_fld_users_username;
		$query = "SELECT ". $db_tbl_users .".*, employee_info.status, employee_info.work_phone FROM ".$db_tbl_users." JOIN employee_info ON employee_info.uid = ". $db_tbl_users .".id ORDER BY ".$db_fld_users_username;
		$r_query = $mysql_link->query($query);
?>

<div style="float:left; width:65%;">
<table border="0">
  <tr>
    <td colspan="4"><a href="useradd.php"><?php echo $txt_overview_user_add?></a> </td>
  </tr>
  <tr bgcolor="#E9E9E9">
    <td><strong><?php echo $txt_overview_col_id?></strong></td>
    <td><strong><?php echo $txt_overview_col_user_login?></strong></td>
    <td><strong><?php echo $txt_overview_col_actions?></strong></td>
    <td><strong><?php echo $txt_overview_col_user_name;?></strong></td>
	
	<td><strong><?php echo $txt_overview_col_user_email;?></strong></td>
	<td><strong><?php echo $txt_overview_col_user_phone;?></strong></td>
	<td><strong><?php echo $txt_overview_col_user_active;?></strong></td>	
	
  </tr>
<?php
		while ($row = $r_query->fetch_assoc()) {
?>
  <tr>
    <td><?php echo $row[$db_fld_users_id];?></td>
    <td><?php echo $row[$db_fld_users_username];?></td>
    <td><a href="useredit.php?action=edit&id=<?php echo $row[$db_fld_users_id];?>"><?php echo $txt_overview_action_edit;?></a> - <a href="useredit.php?action=delete&id=<?php echo $row[$db_fld_users_id];?>" onClick="javascript:return confirm('<?php echo $confirm_deluser_before.$row[$db_fld_users_username].$confirm_deluser_after;?>');"><?php echo $txt_overview_action_delete;?></a> </td>
    <td><?php echo $row[$db_fld_users_realname];?></td>
	<td><?php echo $row[$db_fld_users_email];?></td>
	<td><?php echo $row['work_phone'];?></td>
	<td><?php echo $row['status'];?></td>
  </tr>
<?php
		}
?>
  <tr>
    <td colspan="4"><a href="useradd.php"><?php echo $txt_overview_user_add;?></a> </td>
  </tr>
</table>
</div>
<div style="float:left; width:33%;">
<h1><?php echo $txt_overview_groups_title?></h1>
<?php
$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
	if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
$query = "SELECT ".$db_fld_groups_id.", ".$db_fld_groups_name.", ".$db_fld_groups_description." FROM ".$db_tbl_groups." ORDER BY ".$db_fld_groups_name;
$r_query = $mysql_link->query($query);
?>
<table border="0">
  <tr bgcolor="#E9E9E9">
    <td><strong><?php echo $txt_overview_col_id?></strong></td>
    <td><strong><?php echo $txt_overview_col_group_name?></strong></td>
	<td><strong></strong></td>
    <td><strong><?php echo $txt_overview_col_actions?></strong></td>
    <td><strong><?php echo $txt_overview_col_group_desc?></strong></td>
  </tr>
<?php
		while ($row = $r_query->fetch_assoc()) {
			// Also counts the number of members
			$count_query = "SELECT DISTINCT COUNT(".$db_fld_relation_gid.") FROM ".$db_tbl_relation." WHERE ".$db_fld_relation_gid."=".$row[$db_fld_groups_id];
			$r_count_query = $mysql_link->query($count_query);
			$row_count_query = $r_count_query->fetch_assoc();
?>
  <tr>
    <td><?php echo $row[$db_fld_groups_id]?></td>
    <td><?php echo $row[$db_fld_groups_name]?></td>
	<td>(temp removed count <?php//echo $row_count_query[0]?> <?php echo $txt_overview_members?>)</td>
    <td><a href="groupedit.php?action=edit&id=<?php echo $row[$db_fld_groups_id]?>"><?php echo $txt_overview_action_edit?></a>
<?php
	// Prevents deleting the admin group
	if ($row[$db_fld_groups_name] <> 'admin') {
?>
     - <a href="groupedit.php?action=delete&id=<?php echo $row[$db_fld_groups_id]?>" onClick="javascript:return confirm('<?php echo $confirm_delgroup_before.$row[$db_fld_groups_name].$confirm_delgroup_after?>');"><?php echo $txt_overview_action_delete?></a>
<?php
	}
?>
    </td>
    <td><?php echo $row[$db_fld_groups_description]?></td>
  </tr>
<?php
		}
?>
  <tr>
    <td colspan="5"><a href="groupadd.php"><?php echo $txt_overview_group_add?></a> </td>
  </tr>
</div>
</table>
<p class="textcopy"><?php echo $phpauth_version?></p>

</body>
</html>
