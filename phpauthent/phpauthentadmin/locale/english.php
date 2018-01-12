<?php
// $Author: vincentarn $
// $Date: 2005/04/17 22:13:39 $
// $Id: english.php,v 1.7 2005/04/17 22:13:39 vincentarn Exp $
// $Revision: 1.7 $

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
	/* 
		phpAuthentAdmin Translation file
		ENGLISH Language
		-----------------------------------------------------------------------
		Translated by : vincentarn (vincentarn@users.sourceforge.net)
		-----------------------------------------------------------------------
		NOTE FOR TRANSLATORS
		If you did or wish to translate this file in your native language not already
		supported in phpAuthent, please feel free to submit it to the project.
		For this, you can either email it to vincentarn@users.sourceforge.net or submit
		it as a Patch (Internationalization category). You need a sourceforge.net account
		to submit patches. Thanks. 
		
		phpAuthent PHP Security Module : http://phpauth.sf.net
	*/ 
	// Page titles
	$page_title_overview 	= "Overview";
	$page_title_group_add 	= "Add a new group";
	$page_title_group_edit 	= "Edit group "; // This text is followed by the group name, please keep trailing space.
	$page_title_user_add 	= "Add a new user";
	$page_title_user_edit 	= "Edit user "; // This text is followed by the user login, please keep trailing space.
	
	// Menu items
	$menu_link_overview 	= "Overview";
	$menu_link_homepage		= "Homepage";
	$menu_link_logout 		= "Logout";
	
	// Overview (index.php)
	$txt_overview_pageintro			= "This is the overview page of your phpAuthent database.<br>This is the central point for users and groups management.";
	$txt_overview_users_title 		= "Users overview";
	$txt_overview_user_add			= "Add a new user";
	$txt_overview_group_add			= "Add a new group";
	$txt_overview_groups_title 		= "Groups overview";
	$txt_overview_col_id			= "ID";
	$txt_overview_col_user_login	= "Username";
	$txt_overview_col_actions		= "Actions";
	$txt_overview_col_user_name		= "Name";
	$txt_overview_col_group_name	= "Name";
	$txt_overview_col_group_desc	= "Group description";
	
	$txt_overview_col_user_status	= "Status";
	$txt_overview_col_user_street	= "Street";
	$txt_overview_col_user_city		= "City";
	$txt_overview_col_user_province	= "Prov";
	$txt_overview_col_user_postal	= "Postal";
	$txt_overview_col_user_start	= "Start Date";
	$txt_overview_col_user_dob		= "DOB";
	$txt_overview_col_user_title	= "Title";
	$txt_overview_col_user_phone	= "Phone";
	$txt_overview_col_user_email	= "Email";
	$txt_overview_col_user_active	= "Active";
		
	$txt_overview_members			= "members";
	$txt_overview_action_delete		= "Delete";
	$txt_overview_action_edit		= "Edit";
	
		
	// Group add (groupadd.php)
	$txt_groupadd_pageintro			= "Enter a name and a description and click create.";
	$txt_groupadd_field_name		= "Name";
	$txt_groupadd_field_desc		= "Description";
	$txt_groupadd_button_create		= "Create";
	
	// User add (useradd.php)
	$txt_useradd_pageintro			= "Fill in the following form and click create.";
	$txt_useradd_field_name			= "Name";
	$txt_useradd_field_login		= "Login";
	$txt_useradd_field_password		= "Password";
	$txt_useradd_field_email		= "Email";
	
	$txt_useradd_field_status		= "Status";
	$txt_useradd_field_street		= "Street";
	$txt_useradd_field_city			= "City";
	$txt_useradd_field_prov			= "Prov";
	$txt_useradd_field_postal		= "Postal";
	$txt_useradd_field_start		= "Start Date";
	$txt_useradd_field_dob			= "Date of Birth";
	$txt_useradd_field_title		= "Title";
	$txt_useradd_field_phone		= "Phone";
	
	$txt_useradd_button_create		= "Create";
	
	// Group edit (groupedit.php)
	$txt_groupedit_pageintro		= "This is the group edition page. For the group you selected, you can change its details as well as managing users membership.";
	$txt_groupedit_details_title	= "Group details";
	$txt_groupedit_field_name		= "Name";
	$txt_groupedit_field_desc		= "Description";
	$txt_groupedit_field_memberships 	= "Group memberships";
	$txt_groupedit_field_db_id			= "Database group ID";
	$txt_groupedit_field_avail_users	= "Available users";
	$txt_groupedit_field_group_members 	= "Group members";
	$txt_groupedit_button_apply_changes	= "Apply changes";
	$txt_groupedit_button_cancel		= "Cancel";
	$txt_groupedit_button_add			= "Add";
	$txt_groupedit_button_remove		= "Remove";
	$txt_groupedit_footer_text			= "Changes must be applied for each updated field separately";
	
	// User edit (useredit.php)
	$txt_useredit_pageintro			= "This is the user edition page.";
	$txt_useredit_details_title		= "User details";
	$txt_useredit_field_name		= "Name";
	$txt_useredit_field_login		= "Login";
	$txt_useredit_field_password	= "Password";
	$txt_useredit_field_email		= "Email";
	$txt_useredit_field_creation_date 	= "Creation date";
	$txt_useredit_field_last_login	= "Last login";
	$txt_useredit_field_connections	= "Connections";
	$txt_useredit_field_db_id		= "Database user ID";
	$txt_useredit_button_apply_changes	= "Apply changes";
	$txt_useredit_footer_text			= "Changes must be applied for each updated field separately";
	
	// User deletion confirmation message. This message is in 2 parts in order to fill in with user name in the middle.
	$confirm_deluser_before = "Do you really want to delete the user "; 
	$confirm_deluser_after = " ?";
	
	// Group deletion confirmation message. This message is in 2 parts in order to fill in with group name in the middle.
	$confirm_delgroup_before = "Do you really want to delete the group ";
	$confirm_delgroup_after  = " ?";
	
	// Error messages
	$err = array (
		"001" => "Username and password are required",
		"002" => "This username already exists",
		"003" => "Name is required",
		"004" => "This group already exists",
		"005" => "User could not be deleted",
		"006" => "Group could not be deleted",
		"007" => "Impossible to rename group",
		"008" => "Error while updating group membership",
		"009" => "Impossible to rename user",
		"010" => "Impossible to change user password",
		"011" => "Wrong username or password",
		"012" => "Access denied, authentication required",
		"013" => "Impossible to set new real name",
		"014" => "Impossible to set new description",
		"015" => "Failed to update email",
		"016" => "The admin group MUST CONTAIN at least 1 user,<br>otherwise you won't be able to access anymore this administration interface",
		"017" => "You can't delete the user you're connected with.<br>Please reconnect with another admin user.",
		"018" => "You can't remove the current user from the admin group.<br>Please reconnect with another admin user.",
		"019" => "Access rejected. Non sufficient access rights.",
		"020" => "Other Information could not be updated"
	);
	
	// Information or confirmation messages
	$msg = array (
		"001" => "User created successfully",
		"002" => "Group created successfully",
		"003" => "User deleted successfully",
		"004" => "Group deleted successfully",
		"005" => "Group renamed successfully",
		"006" => "Group memberships updated",
		"007" => "User renamed successfully",
		"008" => "Password was updated successfully",
		"009" => "Demo mode. Forbidden action", // user deletion
		"010" => "Demo mode. Forbidden action", // group renaming
		"011" => "Demo mode. Forbidden action", // group memberships update
		"012" => "Demo mode. Forbidden action", // user creation
		"013" => "Demo mode. Forbidden action", // group creation
		"014" => "Demo mode. Forbidden action", // group deletion
		"015" => "Demo mode. Forbidden action", // user login update
		"016" => "Demo mode. Forbidden action", // user password modification
		"017" => "New realname assigned successfully",
		"018" => "Description updated successfully",
		"019" => "Demo mode. Forbidden action", // group description update
		"020" => "Demo mode. Forbidden action", // user name update
		"021" => "Demo mode. Forbidden action", // user email update
		"022" => "Email updated successfully",
		"023" => "Failed! Passwords Don't Match",
		"024" => "Information updated successfully"
	);
	
?>
