<?php
// $Author: vincentarn $
// $Date: 2005/08/28 11:53:56 $
// $Id: index.php,v 1.12 2005/08/28 11:53:56 vincentarn Exp $
// $Revision: 1.12 $

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
ini_set('display_errors',1); 
error_reporting(E_ALL);
	require_once("phpauthent_core.php");
	echo"here";
	require_once("phpauthent_config.php");
echo"here";
	require_once("phpauthentadmin/locale/".$phpauth_language);
	//simplePageProtect();	
	//echo"here";	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>phpAuthent</title>
<link href="phpauthentadmin/css/phpauth.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {
	font-family: "Courier New", Courier, mono;
	font-size: small;
}
-->
</style>
</head>
<body>
<table width="100%" border="0" cellpadding="0">
  <tr>
    <td valign="top" class="titleapp">phpAuthent</td>
  </tr>
  <tr>
    <td valign="bottom" class="titlepage">Welcome page </td>
  </tr>
  <tr>
    <td valign="top" class="headmenu"><a href="<?php echo $phpauth_successfull_login_target;?>">
      </a></td>
  </tr>
</table>
<p>Welcome to phpAuthent !<br>
  You reached this page after your login because it is the one defined in your configuration file :</p>
<p class="style1">// Target of a successful login<br>
$phpauth_successfull_login_target = &quot;/phpauthent/index.php&quot;;</p>
<p><strong>What can you do next ?</strong></p>
<ul>
  <li> Access the <a href="phpauthentadmin/">phpAuthent Administration</a> and create users and groups.<br>
    <em>You need to belong to the admin group to access phpAuthent Administration, otherwise you'll be asked to authenticate with an administrator account. </em></li>
  <li>Learn how to integrate phpAuthent by reading the  <a href="doc/userguide.pdf" target="_doc">Configuration and Integration guide</a> (Adobe PDF).</li>
  <li><a href="phpauthent_core.php?action=logout">Logout</a> from this page. </li>
</ul>
<p><strong>Read me please ! </strong></p>
<ul>
  <li><strong>This page is provided as a sample</strong> so that you can confirm that phpAuthent was successfully installed. You should at term of integration provide a page to redirect after a successful login and logout (typically, it's your web site root page).</li>
  <li><strong>After a logout from this page</strong> (if you did not change the default configuration), you'll be prompted for a new user authentication with the message 
  <strong>Access denied. Authentication required.<br>
  </strong>The reason is that the default logout target page is defined on this welcome page, which itself implements simple page security and requires a valid user to be shown.<br>
  <span class="style1">// Target of a successful logout<br>
  $phpauth_successfull_logout_target = &quot;/phpauthent/index.php&quot;; </span><br>
  If you redirect to a simple page (or your web page root) which does not require authentication, everything will go fine. </li>
</ul>
<p class="textcopy">
  <?php echo $phpauth_version;?>
</p>
<p>&nbsp;</p>
</body>
