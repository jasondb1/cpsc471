<?php
// $Author: vincentarn $
// $Date: 2005/04/19 22:42:16 $
// $Id: phpauthent_login.php,v 1.5 2005/04/19 22:42:16 vincentarn Exp $
// $Revision: 1.5 $

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
	require_once("phpauthent_core.php");
	require_once("phpauthent_config.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>phpAuthent Login</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Georgia, Times New Roman, Times, serif;
}
-->
</style></head>

<body>

<h3>Login page</h3>
<?php
	if (isset($_GET['err']) && ($_GET['err'] == '011')) {
		print "<p><strong>Invalid username or password</strong></p>";
	}
	if (isset($_GET['err']) && ($_GET['err'] == '012')) {
		print "<p><strong>Access denied. Authentication required</strong></p>";
	}
	if (isset($_GET['err']) && ($_GET['err'] == '019')) {
		print "<p><strong>Access denied. You do not have necessary authorizations</strong></p>";
	}
?>
<form name="loginform" method="post" action="<?php echo $phpauth_loginform_action;?>">
  <table width="500" border="0">
    <tr>
      <td width="84">Username:</td>
      <td width="406"><input name="<?php echo $phpauth_loginform_username;?>" type="text" id="username"></td>
    </tr>
    <tr>
      <td>Password:</td>
      <td><input name="<?php echo $phpauth_loginform_password;?>" type="password" id="password"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td> <input type="submit" name="Submit" value="Submit">
      <input type="reset" name="Reset" value="Reset"></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>
