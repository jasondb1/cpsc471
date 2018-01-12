<?php
// $Author: vincentarn $
// $Date: 2005/08/26 19:43:25 $
// $Id: polish.php,v 1.1 2005/08/26 19:43:25 vincentarn Exp $
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
		POLISH Language - charset ISO-8859-2
		-----------------------------------------------------------------------
		Translated by : WojD (wojtek@serwisy.net)
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
	$page_title_overview 	= "Zarz±dzanie";
	$page_title_group_add 	= "Dodaj now± grupê";
	$page_title_group_edit 	= "Edycja grupy "; // This text is followed by the group name, please keep trailing space.
	$page_title_user_add 	= "Dodaj nowego u¿ytkownika";
	$page_title_user_edit 	= "Edycja u¿ytkownika "; // This text is followed by the user login, please keep trailing space.

	// Menu items
	$menu_link_overview 	= "Zarz±dzanie";
	$menu_link_homepage		= "Strona g³ówna";
	$menu_link_logout 		= "Wyloguj";

	// Overview (index.php)
	$txt_overview_pageintro			= "Tutaj mo¿esz zarz±dzaæ baz± u¿ytkowników.<br>";
	$txt_overview_users_title 		= "Zarz±dzanie u¿ytkownikami";
	$txt_overview_user_add			= "Dodaj nowego u¿ytkownika";
	$txt_overview_group_add			= "Dodaj now± grupê";
	$txt_overview_groups_title 		= "Zarz±dzanie grupami";
	$txt_overview_col_id			= "ID";
	$txt_overview_col_user_login	= "U¿ytkownik";
	$txt_overview_col_actions		= "Akcje";
	$txt_overview_col_user_name		= "Login";
	$txt_overview_col_group_name	= "Nazwa grupy";
	$txt_overview_col_group_desc	= "Opis grupy";
	$txt_overview_members			= "cz³onkowie grupy";
	$txt_overview_action_delete		= "Usuñ";
	$txt_overview_action_edit		= "Edycja";

	// Group add (groupadd.php)
	$txt_groupadd_pageintro			= "Podaj nazwê grupy, opis i kliknij Utwórz.";
	$txt_groupadd_field_name		= "Nazwa";
	$txt_groupadd_field_desc		= "Opis";
	$txt_groupadd_button_create		= "Utwórz";

	// User add (useradd.php)
	$txt_useradd_pageintro			= "Podaj nazwê u¿ytkownika, login, has³o oraz e-mail uzytkownika i kliknij Utwórz.";
	$txt_useradd_field_name			= "Nazwa u¿ytkownika";
	$txt_useradd_field_login		= "Login";
	$txt_useradd_field_password		= "Has³o";
	$txt_useradd_field_email		= "E-mail";
	$txt_useradd_button_create		= "Utwórz";

	// Group edit (groupedit.php)
	$txt_groupedit_pageintro		= "Tutaj mo¿esz zarz±dzaæ grupami - zmieniæ opis grupy oraz przynale¿no¶æ u¿ytkowników.";
	$txt_groupedit_details_title	= "Dane grupy";
	$txt_groupedit_field_name		= "Nazwa";
	$txt_groupedit_field_desc		= "Opis";
	$txt_groupedit_field_memberships 	= "Cz³onkowie grupy";
	$txt_groupedit_field_db_id			= "ID grupy w bazie";
	$txt_groupedit_field_avail_users	= "Dostêpni u¿ytkownicy";
	$txt_groupedit_field_group_members 	= "Cz³onkowie grupy";
	$txt_groupedit_button_apply_changes	= "Wprowad¼ zmiany";
	$txt_groupedit_button_cancel		= "Anuluj";
	$txt_groupedit_button_add			= "Dodaj";
	$txt_groupedit_button_remove		= "Usuñ z grupy";
	$txt_groupedit_footer_text			= "Zmiany musz± byæ wprowadzane dla ka¿dego pola osobno.";

	// User edit (useredit.php)
	$txt_useredit_pageintro			= "Tutaj mo¿esz zarz±dzaæ u¿ytkownikami.";
	$txt_useredit_details_title		= "Dane u¿ytkownika";
	$txt_useredit_field_name		= "Nazwa";
	$txt_useredit_field_login		= "Login";
	$txt_useredit_field_password	= "Has³o";
	$txt_useredit_field_email		= "E-mail";
	$txt_useredit_field_creation_date 	= "Data utworzenia";
	$txt_useredit_field_last_login	= "Ostatnie logowanie";
	$txt_useredit_field_connections	= "Po³±czenia";
	$txt_useredit_field_db_id		= "ID u¿ytkownika w bazie";
	$txt_useredit_button_apply_changes	= "Wprowad¼ zmiany";
	$txt_useredit_footer_text			= "Zmiany musz± byæ wprowadzane dla ka¿dego pola osobno.";

	// User deletion confirmation message. This message is in 2 parts in order to fill in with user name in the middle.
	$confirm_deluser_before = "Czy na pewno chcesz usun±æ u¿ytkownika  ";
	$confirm_deluser_after = " ?";

	// Group deletion confirmation message. This message is in 2 parts in order to fill in with group name in the middle.
	$confirm_delgroup_before = "Czy na pewno chcesz usun±æ grupê ";
	$confirm_delgroup_after  = " ?";

	// Error messages
	$err = array (
		"001" => "Wymagane s± nazwa i has³o u¿ytkownika",
		"002" => "U¿ytkownik o tej nazwie ju¿ ustnieje",
		"003" => "Nazwa u¿ytkownika jest wymagana",
		"004" => "Taka grupa ju¿ istnieje",
		"005" => "U¿ytkownik nie mo¿e zostaæ usuniêty",
		"006" => "Grupa nie mo¿e zostaæ usuniêta",
		"007" => "Nie mo¿na zmieniæ nazwy grupy",
		"008" => "Wyst±pi³ b³±d przy aktualizacji przynale¿no¶ci do grupy",
		"009" => "Nie mo¿na zmieniæ nazwy u¿ytkownika",
		"010" => "Nie mo¿na zmieniæ has³a u¿ytkownika",
		"011" => "B³êdny login lub has³o",
		"012" => "Dostêp wzbroniony - wymagana jest autoryzacja",
		"013" => "Nie mo¿na zapisaæ nazwy u¿ytkownika",
		"014" => "Nie mo¿na zachowaæ nowego opisu",
		"015" => "Nie mo¿na zapisaæ nowego adresu e-mail",
		"016" => "Grupa admin MUSI ZAWIERAÆ co najmniej jednego u¿ytkownika<br>- w przeciwnym wypadku nie bêdzie mo¿liwy dostêp do panelu administracyjnego.",
		"017" => "Nie mo¿na usun±æ samego siebie.<br>Zaloguj siê jako inny u¿ytkownik z prawami admina.",
		"018" => "Nie mo¿na usun±æ bie¿±cego u¿ytkownika.<br>Zaloguj siê jako inny u¿ytkownik z prawami admina.",
		"019" => "Dostêp zablokowany. Nie masz wystarczaj±cych uprawnieñ."
	);

	// Information or confirmation messages
	$msg = array (
		"001" => "U¿ytkownik zosta³ utworzony pomy¶lnie.",
		"002" => "Grupa zosta³a utworzona pomy¶lnie.",
		"003" => "U¿ytkownik zosta³ usuniêty pomy¶lnie.",
		"004" => "Grupa zosta³a usuniêta pomy¶lnie.",
		"005" => "Nazwa grupy zosta³a zmieniona pomy¶lnie.",
		"006" => "Przynale¿no¶æ do grupy zosta³a zaktualizowana pomy¶lnie.",
		"007" => "Nazwa u¿ytkownika zosta³a zmieniona pomy¶lnie.",
		"008" => "Has³o zosta³o zaktualizowane pomy¶lnie",
		"009" => "Tryb demo. Akcja zabroniona.", // user deletion
		"010" => "Tryb demo. Akcja zabroniona.", // group renaming
		"011" => "Tryb demo. Akcja zabroniona.", // group memberships update
		"012" => "Tryb demo. Akcja zabroniona.", // user creation
		"013" => "Tryb demo. Akcja zabroniona.", // group creation
		"014" => "Tryb demo. Akcja zabroniona.", // group deletion
		"015" => "Tryb demo. Akcja zabroniona.", // user login update
		"016" => "Tryb demo. Akcja zabroniona.", // user password modification
		"017" => "NOwa nazwa u¿ytkownika zosta³ zapisana pomy¶lnie.",
		"018" => "Opis zosta³ zaktualizowany pomy¶lnie.",
		"019" => "Tryb demo. Akcja zabroniona.", // group description update
		"020" => "Tryb demo. Akcja zabroniona.", // user name update
		"021" => "Tryb demo. Akcja zabroniona.", // user email update
		"022" => "E-mail zosta³ zaktualizowany pomy¶lnie."
	);

?>
