<?php
// $Author: vincentarn $
// $Date: 2005/04/17 22:13:39 $
// $Id: french.php,v 1.5 2005/04/17 22:13:39 vincentarn Exp $
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
	/* 
		phpAuthentAdmin Translation file
		FRENCH Language
		-----------------------------------------------------------------------
		Translated by : vincentarn (vincentarn@users.sourceforge.net)
		-----------------------------------------------------------------------
		NOTE FOR TRANSLATORS
		If you did or wish translate this file in your native language not already
		supported in phpAuthent, please feel free to submit it to the project.
		For this, you can either email it to vincentarn@users.sourceforge.net or submit
		it as a Patch (Internationalization category). You need a sourceforge.net account
		to submit patches. Thanks.
		
		phpAuthent PHP Security Module : http://phpauth.sf.net
		
	*/ 
	// Page titles
	$page_title_overview 	= "Vue d'ensemble";
	$page_title_group_add 	= "Ajouter un groupe";
	$page_title_group_edit 	= "Edition du groupe "; // This text is followed by the group name, please keep trailing space.
	$page_title_user_add 	= "Ajout d'un utilisateur";
	$page_title_user_edit 	= "Edition de l'utilisateur "; // This text is followed by the user login, please keep trailing space.
	
	// Menu items
	$menu_link_overview 	= "Vue d'ensemble";
	$menu_link_homepage		= "Page principale (sortie)";
	$menu_link_logout 		= "D�connexion";
	
	// Overview (index.php)
	$txt_overview_pageintro			= "Cette page repr�sente la vue d'ensemble de la base phpAuthent.<br>C'est le point central pour la gestion des utilisateurs et des groupes.";
	$txt_overview_users_title 		= "Utilisateurs";
	$txt_overview_user_add			= "Ajouter un utilisateur";
	$txt_overview_group_add			= "Ajouter un groupe";
	$txt_overview_groups_title 		= "Groupes";
	$txt_overview_col_id			= "ID";
	$txt_overview_col_user_login	= "Login";
	$txt_overview_col_actions		= "Actions";
	$txt_overview_col_user_name		= "Nom";
	$txt_overview_col_group_name	= "Nom";
	$txt_overview_col_group_desc	= "Description du groupe";
	$txt_overview_members			= "membres";
	$txt_overview_action_delete		= "Supression";
	$txt_overview_action_edit		= "Edition";
	
	// Group add (groupadd.php)
	$txt_groupadd_pageintro			= "Ins�rer un nom et une description puis cliquez sur Cr�er.";
	$txt_groupadd_field_name		= "Nom";
	$txt_groupadd_field_desc		= "Description";
	$txt_groupadd_button_create		= "Cr�er";
	
	// User add (useradd.php)
	$txt_useradd_pageintro			= "Compl�tez le formulaire suivant puis cliquez sur Cr�er.";
	$txt_useradd_field_name			= "Nom";
	$txt_useradd_field_login		= "Login";
	$txt_useradd_field_password		= "Mot de passe";
	$txt_useradd_field_email		= "Email";
	$txt_useradd_button_create		= "Cr�er";
	
	// Group edit (groupedit.php)
	$txt_groupedit_pageintro		= "Ceci est la page d'�dition d'un groupe. Pour le groupe s�lectionn�, vous pouvez modifier ses d�tails ainsi que les membres du groupe.";
	$txt_groupedit_details_title	= "D�tails";
	$txt_groupedit_field_name		= "Nom";
	$txt_groupedit_field_desc		= "Description";
	$txt_groupedit_field_memberships 	= "Membres du groupe";
	$txt_groupedit_field_db_id			= "ID base de donn�es";
	$txt_groupedit_field_avail_users	= "Utilisateurs disponibles";
	$txt_groupedit_field_group_members 	= "Membres du groupe";
	$txt_groupedit_button_apply_changes	= "Appliquer";
	$txt_groupedit_button_cancel		= "Annuler";
	$txt_groupedit_button_add			= "Ajouter";
	$txt_groupedit_button_remove		= "Supprimer";
	$txt_groupedit_footer_text			= "Les modifications doivent �tre appliqu�es pour chaque champ s�paremment";
	
	// User edit (useredit.php)
	$txt_useredit_pageintro			= "Ceci est la page d'�dition d'un utilisateur.";
	$txt_useredit_details_title		= "D�tails de l'utilisateur";
	$txt_useredit_field_name		= "Nom";
	$txt_useredit_field_login		= "Login";
	$txt_useredit_field_password	= "Mot de passe";
	$txt_useredit_field_email		= "Email";
	$txt_useredit_field_creation_date 	= "Date de cr�ation";
	$txt_useredit_field_last_login	= "Derni�re connexion";
	$txt_useredit_field_connections	= "Nombre de connexions";
	$txt_useredit_field_db_id		= "ID base de donn�es";
	$txt_useredit_button_apply_changes	= "Appliquer";
	$txt_useredit_footer_text			= "Les modifications doivent �tre appliqu�es pour chaque champ s�paremment";
	
	// User deletion confirmation message. This message is in 2 parts in order to fill in with user name in the middle.
	$confirm_deluser_before = "Voulez-vous vraiment supprimer l\'utilisateur "; 
	$confirm_deluser_after = " ?";
	
	// Group deletion confirmation message. This message is in 2 parts in order to fill in with group name in the middle.
	$confirm_delgroup_before = "Voulez-vous vraiment supprimer le groupe ";
	$confirm_delgroup_after  = " ?";
	
	// Error messages
	$err = array (
		"001" => "Login et mot de passe requis",
		"002" => "Ce login est d�j� utilis�",
		"003" => "Nom requis",
		"004" => "Ce groupe existe d�j�",
		"005" => "Erreur lors de la suppression de l'utilisateur",
		"006" => "Erreur lors de la suppression du groupe",
		"007" => "Erreur lors de la mise � jour du nom de groupe",
		"008" => "Erreur lors de la mise � jour des membres du groupe",
		"009" => "Erreur lors de la mise � jour du login de l'utilisateur",
		"010" => "Erreur lors de la mise � jour du mot de passe utilisateur",
		"011" => "Login ou mot de passe incorrect",
		"012" => "Acc�s refus�. Autentification n�cessaire",
		"013" => "Erreur lors de la mise � jour du nom de l'utlisateur",
		"014" => "Erreur lors de la mise � jour de la description",
		"015" => "Erreur lors de la mise � jour de l'adresse email",
		"016" => "Le groupe admin doit contenir AU MOINS 1 utilisateur.<br>Sans cela, vous ne pourrez plus acc�der � la console d'administration",
		"017" => "Vous ne pouvez pas supprimer l'utilisateur avec lequel vous �tes connect�.<br>Veuillez vous reconnecter avec un autre administrateur.",
		"018" => "Vous ne pouvez pas supprimer l'utilisateur courant du groupe admin.<br>Veuillez vous reconnecter avec un autre administrateur.",
		"019" => "Acc�s � cette ressource non autoris�e. Droits d'acc�s insuffisants."
	);
	
	// Information or confirmation messages
	$msg = array (
		"001" => "Utilisateur cr��",
		"002" => "Group cr��",
		"003" => "Utilisateur supprim�",
		"004" => "Group supprim�",
		"005" => "Group renomm�",
		"006" => "Mise � jour des membres du groupe effectu�e",
		"007" => "Mise � jour du login utilisateur effectu�e",
		"008" => "Mise � jour du mot de passe utilisateur effectu�e",
		"009" => "Mode demo. Op�ration interdite", // user deletion
		"010" => "Mode demo. Op�ration interdite", // group renaming
		"011" => "Mode demo. Op�ration interdite", // group memberships update
		"012" => "Mode demo. Op�ration interdite", // user creation
		"013" => "Mode demo. Op�ration interdite", // group creation
		"014" => "Mode demo. Op�ration interdite", // group deletion
		"015" => "Mode demo. Op�ration interdite", // user login update
		"016" => "Mode demo. Op�ration interdite", // user password modification
		"017" => "Mise � jour du nom de l'utilisateur effectu�e",
		"018" => "Mise � jour de la description effectu�e",
		"019" => "Mode demo. Op�ration interdite", // group description update
		"020" => "Mode demo. Op�ration interdite", // user name update
		"021" => "Mode demo. Op�ration interdite", // user email update
		"022" => "Mise � jour de l'adresse email effectu�e"
	);
	
?>