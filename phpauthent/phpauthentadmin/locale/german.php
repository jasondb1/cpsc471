<?php
// $Author: vincentarn $
// $Date: 2005/04/25 16:21:13 $
// $Id: german.php,v 1.1 2005/04/25 16:21:13 vincentarn Exp $
// $Revision: 1.1 $

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
		GERMAN Language
		-----------------------------------------------------------------------
		Translated by : Alexander Baumgartl (abaumgartl@web.de)
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
	$page_title_overview 	= "Überblick";
	$page_title_group_add 	= "Eine neue Gruppe hinzufügen";
	$page_title_group_edit 	= "Gruppe bearbeiten "; // This text is followed by the group name, please keep trailing space.
	$page_title_user_add 	= "Einen neuen Benutzer hinzufügen";
	$page_title_user_edit 	= "Benutzer bearbeiten "; // This text is followed by the user login, please keep trailing space.
	
	// Menu items
	$menu_link_overview 	= "Überblick";
	$menu_link_homepage		= "Homepage";
	$menu_link_logout 		= "Abmelden";
	
	// Overview (index.php)
	$txt_overview_pageintro			= "Dies ist die Übersichtsseite der phpauthent Datenbank.<br>Hier ist die Zentrale der User- und Gruppenverwaltung.";
	$txt_overview_users_title 		= "Benutzer-Übersicht";
	$txt_overview_user_add			= "Einen neuen Benutzer hinzufügen";
	$txt_overview_group_add			= "Eine neue Gruppe hinzufügen";
	$txt_overview_groups_title 		= "Gruppenübersicht";
	$txt_overview_col_id			= "ID";
	$txt_overview_col_user_login	= "Benutzername";
	$txt_overview_col_actions		= "Aktionen";
	$txt_overview_col_user_name		= "Name";
	$txt_overview_col_group_name	= "Name";
	$txt_overview_col_group_desc	= "Gruppenbeschreibung";
	$txt_overview_members			= "Mitglieder";
	$txt_overview_action_delete		= "Löschen";
	$txt_overview_action_edit		= "Editieren";
	
	// Group add (groupadd.php)
	$txt_groupadd_pageintro			= "Bitte Namen und Beschreibung eingeben. Dann *Erstellen* anklicken.";
	$txt_groupadd_field_name		= "Name";
	$txt_groupadd_field_desc		= "Beschreibung";
	$txt_groupadd_button_create		= "Erstellen";
	
	// User add (useradd.php)
	$txt_useradd_pageintro			= "Bitte das Formular ausfüllen und *Erstellen* anklicken.";
	$txt_useradd_field_name			= "Name";
	$txt_useradd_field_login		= "Login";
	$txt_useradd_field_password		= "Passwort";
	$txt_useradd_field_email		= "Email";
	$txt_useradd_button_create		= "Erstellen";
	
	// Group edit (groupedit.php)
	$txt_groupedit_pageintro		= "Dies ist die Seite zur Bearbeitung von Gruppen. Wenn du eine Gruppe wählst, kannst du ihre Detailinformationen ändern und die Mitgliedschaft der Benutzer anpassen.";
	$txt_groupedit_details_title	= "Gruppendetails";
	$txt_groupedit_field_name		= "Name";
	$txt_groupedit_field_desc		= "Beschreibung";
	$txt_groupedit_field_memberships 	= "Gruppenmitgliedschaft";
	$txt_groupedit_field_db_id			= "GruppenID der Datenbank";
	$txt_groupedit_field_avail_users	= "Verfügbare Benutzer";
	$txt_groupedit_field_group_members 	= "Gruppenmitglieder";
	$txt_groupedit_button_apply_changes	= "Änderungen vornehmen";
	$txt_groupedit_button_cancel		= "Abbrechen";
	$txt_groupedit_button_add			= "Hinzufügen";
	$txt_groupedit_button_remove		= "Entfernen";
	$txt_groupedit_footer_text			= "Änderungen müssen für jedes Feld einzeln vorgenommen und bestätigt werden.";
	
	// User edit (useredit.php)
	$txt_useredit_pageintro			= "Das ist die Benutzerverwaltung.";
	$txt_useredit_details_title		= "Benutzerinformationen";
	$txt_useredit_field_name		= "Name";
	$txt_useredit_field_login		= "Login";
	$txt_useredit_field_password	= "Passwort";
	$txt_useredit_field_email		= "Email";
	$txt_useredit_field_creation_date 	= "Erstelldatum";
	$txt_useredit_field_last_login	= "Letzte Anmeldung";
	$txt_useredit_field_connections	= "Verbindungen";
	$txt_useredit_field_db_id		= "BenutzerID in der Datenbank";
	$txt_useredit_button_apply_changes	= "Änderungen vornehmen";
	$txt_useredit_footer_text			= "Änderungen müssen für jedes Feld einzeln vorgenommen und bestätigt werden.";
	
	// User deletion confirmation message. This message is in 2 parts in order to fill in with user name in the middle.
	$confirm_deluser_before = "Willst du diesen Benutzer wirklich löschen "; 
	$confirm_deluser_after = " ?";
	
	// Group deletion confirmation message. This message is in 2 parts in order to fill in with group name in the middle.
	$confirm_delgroup_before = "Willst du diese Gruppe wirklich löschen ";
	$confirm_delgroup_after  = " ?";
	
	// Error messages
	$err = array (
		"001" => "Benutzername und Passwort sind erforderlich",
		"002" => "Dieser Benutzername existiert bereits",
		"003" => "Name ist erforderlich",
		"004" => "Diese Gruppe existiert bereits",
		"005" => "Der Benutzer konnte nicht gelöscht werden",
		"006" => "Die Gruppe konnte nicht gelöscht werden",
		"007" => "Die Gruppe konnte nicht umbenannt werden",
		"008" => "Ein Fehler ist bei der Aktualisierung der Gruppe aufgetreten",
		"009" => "Der Benutzer konnte nicht umbenannt werden",
		"010" => "Das Benutzerpasswort konnte nicht geändert werden",
		"011" => "Falscher Benutzername oder falsches Passwort",
		"012" => "Zugriff verweigert. Authentifizierung notwendig",
		"013" => "Der neue Name konnte nicht erstellt werden",
		"014" => "Die neue Beschreibung konnte nicht erstellt werden",
		"015" => "Die Emailadresse konnte nicht erstellt werden",
		"016" => "Die Gruppe Admin MUSS wenigstens 1 User beinhalten,<br> da ansonsten kein Zugriff mehr auf die Administrationsoberfläche möglich ist",
		"017" => "Du kannst den Benutzer nicht löschen, mit dem du eingeloggt bist.<br> Bitte verbinde dich mit dem Adminkonto.",
		"018" => "Du kannst den aktuellen User nicht aus der Admingruppe nehmen. Bitte verbinde dich mit einem anderen Adminkonto.",
		"019" => "Zugriff zurückgewiesen. Keine ausreichenden Zugriffsrechte."
	);
	
	// Information or confirmation messages
	$msg = array (
		"001" => "Benutzer wurde erfolgreich erstellt",
		"002" => "Gruppe wurde erfolgreich erstellt",
		"003" => "Benutzer wurde erfolgreich gelöscht",
		"004" => "Gruppe wurde erfolgreich gelöscht",
		"005" => "Gruppe wurde erfolgreich umbenannt",
		"006" => "Gruppenmitgliedschaft wurde erfolgreich aktualisiert",
		"007" => "Benutzer wurde erfolgreich umbenannt",
		"008" => "Passwort wurde erfolgreich aktualisiert",
		"009" => "Demomodus. Dieser Vorgang ist nicht erlaubt", // user deletion
		"010" => "Demomodus. Dieser Vorgang ist nicht erlaubt", // group renaming
		"011" => "Demomodus. Dieser Vorgang ist nicht erlaubt", // group memberships update
		"012" => "Demomodus. Dieser Vorgang ist nicht erlaubt", // user creation
		"013" => "Demomodus. Dieser Vorgang ist nicht erlaubt", // group creation
		"014" => "Demomodus. Dieser Vorgang ist nicht erlaubt", // group deletion
		"015" => "Demomodus. Dieser Vorgang ist nicht erlaubt", // user login update
		"016" => "Demomodus. Dieser Vorgang ist nicht erlaubt", // user password modification
		"017" => "Ein neuer Name wurde erfolgreich zugeordnet.",
		"018" => "Beschreibung wurde erfolgreich aktualisiert",
		"019" => "Demomodus. Dieser Vorgang ist nicht erlaubt", // group description update
		"020" => "Demomodus. Dieser Vorgang ist nicht erlaubt", // user name update
		"021" => "Demomodus. Dieser Vorgang ist nicht erlaubt", // user email update
		"022" => "Email wurde erfolgreich aktualisiert"
	);
	
?>