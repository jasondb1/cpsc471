<?php
// $Author: vincentarn $
// $Date: 2005/08/28 11:45:25 $
// $Id: phpauthent_config.php,v 1.13 2005/08/28 11:45:25 vincentarn Exp $
// $Revision: 1.13 $

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
	//ini_set('display_errors',1); 
	//error_reporting(E_ALL);
	//require_once("../_config.php");
	//require($_SERVER['DOCUMENT_ROOT'].'/assurance/_config.php');
	// START OF CONFIGURATION
	// Edit the following lines to suit your configuration
	
	// Main configuration
	// Database (MySQL) settings
	$db_hostname = 'localhost';
	$db_database = 'cpsc471';
	$db_username = 'cpsc471';
	$db_password = 'testing';
	//$db_hostname = $dbhost;
	//$db_database = $dbname;
	//$db_username = $dbusername;
	//$db_password = $dbpass;
	
	// Files location
	// The paths specified below has to be defined as FULL PATHS (beginning with a '/' designing the root
	// of your webserver space).
	// Path to the login page :
	//$phpauth_login = 'phpauthent_login.php';
	$phpauth_login = '../_login.php';
	// Target of a successful login :
	$phpauth_successfull_login_target = "../_employee_menu.php";
	// Target of a successful logout :
	$phpauth_successfull_logout_target = "../index.php";
	
	// Admin security scope
	// When setting admin_always_enabled to true, a user member of the admin group can pass through any
	// security setting (similar to setting the 'admin' group in every groupsList array)
	$admin_always_enabled = true;
	
	// Session lifetime
	// Specify here the number of seconds after which the cookie session handler expires to force a new login
	$session_lifetime     = 1200;	// IN DEVELOPMENT - NOT USED
	
	// Language
	// Specify here the language file (in the phpauthadmin/locale directory) which suits to
	// your language.
	// Available languages are :
	// - English     'english.php'
	// - French      'french.php'
	// - German      'german.php'
	// - Spanish	 'spanish.php'
	// - Polish      'polish.php'
	$phpauth_language = 'english.php';	
	
	// Demo mode
	// When set to true, no edition is possible (rename, delete, assign or create)
	$demo_mode_enabled = false;
	
	// User creation, password wizard (not implemented)
	$enable_password_wizard = false;	// Not used
	$passwords_chars_length = 8;		// Not used
	
	// Encryption secret key
	$phpauthent_enckey = "ZnPbp5yJVjq2Tdo8K6VJWiZkNEfWQdR9";
	
	// END OF CONFIGURATION
	// ------------------------------------------------------------------------
	// Do not modify those lines unless you changed the data structure or customized phpauthent to suit your needs
	
	// Database table and fields
	// Table names
	$db_tbl_users   = 'phpauthent_users';
	$db_tbl_groups  = 'phpauthent_groups'; 
	$db_tbl_relation = 'phpauthent_relation';
	// Fields names (users table)
	$db_fld_users_id           = 'id';
	$db_fld_users_username     = 'username';
	$db_fld_users_password     = 'password';
	$db_fld_users_realname     = 'realname';
	$db_fld_users_email        = 'email';
	$db_fld_users_lastlogin    = 'lastlogin';
	$db_fld_users_connections  = 'numlogins';
	$db_fld_users_creationdate = 'creation';
	
	$db_fld_users_title			= 'title';
	$db_fld_users_phone			= 'phone';
	$db_fld_users_active		= 'active';
	$db_fld_users_status		= 'status';
	$db_fld_users_street		= 'street';
	$db_fld_users_city			= 'city';
	$db_fld_users_prov			= 'prov';
	$db_fld_users_postal		= 'postal';
	$db_fld_users_start			= 'start';
	$db_fld_users_dob			= 'dob';	
	
	// Fields names (groups table)
	$db_fld_groups_id   = 'id';
	$db_fld_groups_name = 'name';
	$db_fld_groups_description = 'description';
	// Fields names (relation table)
	$db_fld_relation_id = 'id';
	$db_fld_relation_gid = 'group_id';
	$db_fld_relation_uid = 'user_id';
	
	// Session variable names
	$sess_user_id   = 'phpauthent_uid';
	$sess_user_name = 'phpauthent_uname';
	
	// Login and logout actions handling
	$phpauth_loginform_action   = "?action=login";
	if (!empty($_GET['refurl'])) {
		$phpauth_loginform_action .= "&refurl=".$_GET['refurl'];
	}
	
	$phpauth_loginform_username = "username";
	$phpauth_loginform_password = "password";
	
	// Version
	$phpauth_version = "Powered by phpAuthent 0.2.1 - <a href='https://sourceforge.net/projects/phpauth/'>Project Home</a>";
?>
