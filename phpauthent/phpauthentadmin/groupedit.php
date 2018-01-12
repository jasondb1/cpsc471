<?php
// $Author: vincentarn $
// $Date: 2005/04/22 08:17:51 $
// $Id: groupedit.php,v 1.11 2005/04/22 08:17:51 vincentarn Exp $
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

	global $g_id;
	
	if (!empty($_GET['id'])) {
		$g_id = $_GET['id'];
	}
	
	if ((!empty($_GET['action'])) && ($_GET['action'] == "delete")) {
		// Delete group with id passed as a parameter
		if ($demo_mode_enabled) {
			header ("Location: groupedit.php?msg=014&id=".$g_id);
			exit;
		}
		//$mysql_link = mysql_connect($db_hostname,$db_username,$db_password) or die("Failed to connect to MySQL");
		//mysql_select_db($db_database);
		$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		$query = "DELETE FROM ".$db_tbl_groups." WHERE ".$db_fld_groups_id."=".$g_id;
		$r_query = $mysql_link->query($query);
		if ($mysql_link->affected_rows <> 1) {
			header ("Location: groupedit.php?err=006");
		} else {
			// Delete successful
			// Now deleting old relations
			$query = "DELETE FROM ".$db_tbl_relation." WHERE ".$db_fld_relation_gid."=".$g_id;
			$r_query = $mysql_link->query($query);
			header ("Location: index.php?msg=004");
		} 
	} else if (!empty($_POST['action'])) {
		if ($_POST['action'] == "rename") {
			// Rename group with id passed as parameter
			if ($demo_mode_enabled) {
				header ("Location: groupedit.php?msg=010&id=".$g_id);
				exit;
			}
			$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
			if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
			$query = "UPDATE ".$db_tbl_groups." SET ".$db_fld_groups_name."='".$_POST['name']."' WHERE ".$db_fld_groups_id."=".$g_id;
			$r_query = $mysql_link->query($query);
			if ($mysql_link->affected_rows <> 1) {
				header ("Location: groupedit.php?err=007&id=".$g_id);
			} else {
				// Update successful
				header ("Location: groupedit.php?msg=005&id=".$g_id);
			} 
	
		} else if ($_POST['action'] == "changedesc") {
			// Rename group with id passed as parameter
			if ($demo_mode_enabled) {
				header ("Location: groupedit.php?msg=019&id=".$g_id);
				exit;
			}
			$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
			if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
			$query = "UPDATE ".$db_tbl_groups." SET ".$db_fld_groups_description."='".$_POST['description']."' WHERE ".$db_fld_groups_id."=".$g_id;
			$r_query = $mysql_link->query($query);
			if ($mysql_link->affected_rows <> 1) {
				header ("Location: groupedit.php?err=014&id=".$g_id);
			} else {
				// Update successful
				header ("Location: groupedit.php?msg=018&id=".$g_id);
			} 
	
		} else if ($_POST['action'] == "update") {
			if ($demo_mode_enabled) {
				header ("Location: groupedit.php?msg=011&id=".$g_id);
				exit;
			}
			// Gets the selected user ids from the POST header
			if (!empty($_POST['itemsright'])) {
				$user_ids = $_POST['itemsright'];
			}
			
			if (($_POST['grpname'] == 'admin') && (empty($_POST['itemsright']))) {
				// Ensure that at least 1 member belongs to the admin group
				header ("Location: groupedit.php?err=016&id=".$g_id);
			} else if (($_POST['grpname'] == 'admin') && (!in_array(getUserId(),$_POST['itemsright']))) {
				// Prevents removing the user we're connected with if admin group edition
				header ("Location: groupedit.php?err=018&id=".$g_id);
			} else {
				// No warning - Not admin group or conditions fullfilled
				$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
				if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
				$query = "DELETE FROM ".$db_tbl_relation." WHERE ".$db_fld_relation_gid."=".$g_id;
				$r_query = $mysql_link->query($query);
				
				if (count($user_ids) > 0) {
					foreach ($user_ids as $user_id) {
						$query = "INSERT INTO ".$db_tbl_relation." (".$db_fld_relation_uid." ,".$db_fld_relation_gid.") VALUES ('".$user_id."','".$g_id."')";
						$r_query = $mysql_link->query($query);
						if ($mysql_link->affected_rows <> 1) {
							header ("Location: groupedit.php?err=008&id=".$g_id);
						} 
					}
				}
				header ("Location: groupedit.php?msg=006&id=".$g_id);
			}
		}
	} 
	
	// Retrieve name from database
	$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
	if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
	$query = "SELECT ".$db_fld_groups_name.", ".$db_fld_groups_description." FROM ".$db_tbl_groups." WHERE ".$db_fld_groups_id."=".$g_id;
	$r_query = $mysql_link->query($query);
	while ($row = $r_query->fetch_assoc()) {
		$group_name = $row[$db_fld_groups_name];
		$group_description = $row[$db_fld_groups_description];
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>phpAuthent Administration</title>
<script>
function move_item(from, to)
{
  var f;
  var SI; /* selected Index */
  if(from.options.length>0)
  {
    for(i=0;i<from.length;i++)
    {
      if(from.options[i].selected)
      {
        SI=from.selectedIndex;
        f=from.options[SI].index;
        to.options[to.length]=new Option(from.options[SI].text,from.options[SI].value);
        from.options[f]=null;
        i--; /* make the loop go through them all */
      }
    }
  }
}

function select_all(to) {
	for(var x = 0; x , to.length; x++){
        to.options[x].selected = true
    }
}
</script>
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
    <td valign="bottom" class="titlepage"><?php echo $page_title_group_edit;?> <em>
      <?php echo $group_name;?>
    </em></td>
  </tr>
  <tr>
    <td valign="top" class="headmenu"><a href="index.php"><?php echo $menu_link_overview;?></a> - <a href="<?php echo $phpauth_successfull_login_target;?>"><?php echo $menu_link_homepage;?></a> - <a href="../phpauthent_core.php?action=logout" class="style1"></a> <a href="../phpauthent_core.php?action=logout"><?php echo $menu_link_logout;?> (<?php echo getUsername();?>)</a></td>
  </tr>
</table>
<p class="style1"><?php echo $txt_groupedit_pageintro;?></p>
<p class="style1">
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
<table border="0" cellpadding="2">
  <tr>
    <td colspan="4" class="titlepage"><?php echo $txt_groupedit_details_title;?></td>
  </tr>
<?php
  // Disable renaming for the admin group
  if ($group_name <> 'admin') {
?>
	  <form name="renameform" method="post" action="groupedit.php?id=<?php echo $g_id;?>">
	    <input type="hidden" name="action" value="rename">
	    <tr>
	      <td bgcolor="#E9E9E9"><span class="style3"><?php echo $txt_groupedit_field_name;?>*</span></td>
	      <td><?php echo $group_name;?></td>
	      <td><input name="name" type="text" maxlength="32"></td>
	      <td><input type="submit" name="Submit" value="<?php echo $txt_groupedit_button_apply_changes;?>"></td>
	    </tr>
	  </form>
<?php
  }
?>
  <form name="changedesc" method="post" action="groupedit.php?id=<?php echo $g_id;?>">
    <input type="hidden" name="action" value="changedesc">
    <tr>
      <td bgcolor="#E9E9E9"><span class="style3"><?php echo $txt_groupedit_field_desc;?>*</span></td>
      <td><?php echo $group_description;?></td>
      <td><input name="description" type="text" id="description" size="40" maxlength="80"></td>
      <td><input type="submit" name="Submit" value="<?php echo $txt_groupedit_button_apply_changes;?>"></td>
    </tr>
  </form>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#E9E9E9" class="style3"><?php echo $txt_groupedit_field_memberships;?></td>
    <td colspan="3">
	<form action="groupedit.php?id=<?php echo $g_id;?>" method="post" name="groupform" id="groupform">
  	<input type="hidden" name="action" value="update">
  	<input type="hidden" name="grpname" value="<?php echo $group_name;?>">
	<table width="100%" border="0" cellpadding="2">
      <tr valign="top" bgcolor="#E9E9E9" class="style3">
        <td width="107" nowrap bgcolor="#E9E9E9"><div align="center"><strong><?php echo $txt_groupedit_field_avail_users;?></strong></div></td>
        <td width="25" nowrap>&nbsp;</td>
        <td width="111" nowrap><div align="center"><strong><?php echo $txt_groupedit_field_group_members;?></strong></div></td>
      </tr>
      <tr>
        <td><div align="center">
            <?php
		$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		
		// Turn around for MySQL < 4.1
		// We need to first reach the list of users IN the group and the filter the ALL USERS LIST based on this 1st query
		$query = "SELECT DISTINCT a.".$db_fld_users_id.", a.".$db_fld_users_username." FROM ".$db_tbl_users." a, ".$db_tbl_relation." b WHERE a.".$db_fld_users_id."=b.".$db_fld_relation_uid." AND b.".$db_fld_relation_gid."=".$g_id." ORDER BY ".$db_fld_users_username;
		$r_query = $mysql_link->query($query);
		// Initialize an array that will contain group members ids
		$group_users = array();
		while ($row = $r_query->fetch_assoc()) {
			array_push($group_users,$row[$db_fld_users_id]);
		}
		// List all users
		$query = "SELECT ".$db_fld_users_id.", ".$db_fld_users_username." FROM ".$db_tbl_users;
		$r_query = $mysql_link->query($query);
?>
            <select name="itemsleft" size="10" multiple id="itemsleft">
              <?php
			while ($row = $r_query->fetch_assoc()) {
				// Filtering list so that only users NOT belonging to the group are displayed
				if (! in_array($row[$db_fld_users_id],$group_users)) {
?>
              <option value="<?php echo $row[$db_fld_users_id];?>">
              <?php echo $row[$db_fld_users_username];?>
              </option>
              <?php
				}
			}
?>
            </select>
        </div></td>
        <td><div align="center">
              <input type="button" value = " <?php echo $txt_groupedit_button_add;?> &gt;  " onClick="move_item(itemsleft, itemsright)">
              <br>
              <input type="button" value = "< <?php echo $txt_groupedit_button_remove;?> " onClick="move_item(itemsright,itemsleft)">
        </div></td>
        <td><div align="center">
            <?php
		$mysql_link = new MySQLi($db_hostname, $db_username, $db_password, $db_database);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		$query = "SELECT DISTINCT a.".$db_fld_users_id.", a.".$db_fld_users_username." FROM ".$db_tbl_users." a, ".$db_tbl_relation." b WHERE a.".$db_fld_users_id."=b.".$db_fld_relation_uid." AND b.".$db_fld_relation_gid."=".$g_id." ORDER BY ".$db_fld_users_username;
		$r_query = $mysql_link->query($query);
?>
            <select name="itemsright[]" size="10" multiple id="itemsright">
              <?php
		while ($row = $r_query->fetch_assoc()) {
?>
              <option value="<?php echo $row[$db_fld_users_id];?>">
              <?php echo $row[$db_fld_users_username];?>
              </option>
              <?php
		}
?>
            </select>
        </div></td>
      </tr>
      <tr>
        <td colspan="3"><div align="center">
            <input type="submit" name="Submit" value="<?php echo $txt_groupedit_button_apply_changes;?>" onClick="select_all(itemsright)">
            <input type="reset" name="Reset" value="<?php echo $txt_groupedit_button_cancel;?>">
        </div></td>
      </tr>
    </table>
	</form>
	</td>
  </tr>
  <tr>
    <td valign="top" class="style3">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#E9E9E9"><strong class="style3"><?php echo $txt_groupedit_field_db_id;?></strong></td>
    <td><?php echo $g_id;?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p><span class="style1">* <?php echo $txt_groupedit_footer_text;?></span></p>
<p class="textcopy"><?php echo $phpauth_version;?></p>

</body>
</html>
