<?php
// $Author: vincentarn $
// $Date: 2005/08/26 21:34:40 $
// $Id: spanish.php,v 1.2 2005/08/26 21:34:40 vincentarn Exp $
// $Revision: 1.2 $

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
		Translated by : Manuel Aponte ()
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
	$page_title_overview 	= "Vista general";
	$page_title_group_add 	= "Añadir un grupo";
	$page_title_group_edit 	= "Editar el grupo "; // This text is followed by the group name, please keep trailing space.
	$page_title_user_add 	= "Añadir un usuario";
	$page_title_user_edit 	= "Editar el uilisador "; // This text is followed by the user login, please keep trailing space.
	
	// Menu items
	$menu_link_overview 	= "Vista general";
	$menu_link_homepage		= "Pagina principal (salida)";
	$menu_link_logout 		= "Desconección";
	
	// Overview (index.php)
	$txt_overview_pageintro			= "Esta página representa la vista general de la base phpAuthent.<br>Es el punto central para la gestion de los usuarioes y de los grupos.";
	$txt_overview_users_title 		= "Usuarioes";
	$txt_overview_user_add			= "Añadir un usuario";
	$txt_overview_group_add			= "Añadir un groupo";
	$txt_overview_groups_title 		= "Grupos";
	$txt_overview_col_id			= "ID";
	$txt_overview_col_user_login	= "Login";
	$txt_overview_col_actions		= "Acciones";
	$txt_overview_col_user_name		= "Nombres";
	$txt_overview_col_group_name	= "Nombre";
	$txt_overview_col_group_desc	= "Descripción del grupo";
	$txt_overview_members			= "Miembros";
	$txt_overview_action_delete		= "Supreción";
	$txt_overview_action_edit		= "Edition";
	
	// Group add (groupadd.php)
	$txt_groupadd_pageintro			= "Añadir un nombre y una descripción y pinchar sobre Crear.";
	$txt_groupadd_field_name		= "Nombre";
	$txt_groupadd_field_desc		= "Descripción";
	$txt_groupadd_button_create		= "Crear";
	
	// User add (useradd.php)
	$txt_useradd_pageintro			= "Completar el formulario siguiente y pinchar sobre Crear.";
	$txt_useradd_field_name			= "Nombre";
	$txt_useradd_field_login		= "Login";
	$txt_useradd_field_password		= "Contraseña";
	$txt_useradd_field_email		= "Email";
	$txt_useradd_button_create		= "Crear";
	
	// Group edit (groupedit.php)
	$txt_groupedit_pageintro		= "Esta es la pagina de edición de un grupo. Para el grupo seleccionado, podeís modificar sus detailles y los miembros del grupo.";
	$txt_groupedit_details_title	= "Detalles";
	$txt_groupedit_field_name		= "Nombre";
	$txt_groupedit_field_desc		= "Descripción";
	$txt_groupedit_field_memberships 	= "Miembros del grupo";
	$txt_groupedit_field_db_id			= "ID base de datos";
	$txt_groupedit_field_avail_users	= "usuarioes disponible";
	$txt_groupedit_field_group_members 	= "Miembros del grupo";
	$txt_groupedit_button_apply_changes	= "Aplicar";
	$txt_groupedit_button_cancel		= "Cancelar";
	$txt_groupedit_button_add			= "Añadir";
	$txt_groupedit_button_remove		= "Borrar";
	$txt_groupedit_footer_text			= "Las modificaciones deben ser aplicadas de manera separada para cada informacion";
	
	// User edit (useredit.php)
	$txt_useredit_pageintro			= "Esta es la pagina de un usuario.";
	$txt_useredit_details_title		= "Detalles del usuario";
	$txt_useredit_field_name		= "Nombre";
	$txt_useredit_field_login		= "Login";
	$txt_useredit_field_password	= "Contraseña";
	$txt_useredit_field_email		= "Email";
	$txt_useredit_field_creation_date 	= "Fecha de creación";
	$txt_useredit_field_last_login	= "Ultima conexión";
	$txt_useredit_field_connections	= "Numero de conexiones";
	$txt_useredit_field_db_id		= "ID base de datos";
	$txt_useredit_button_apply_changes	= "Aplicar";
	$txt_useredit_footer_text			= "Las modificaciones deben ser aplicadas de manera separada para cada información";
	
	// User deletion confirmation message. This message is in 2 parts in order to fill in with user name in the middle.
	$confirm_deluser_before = "Desea realmente suprimir el usuario ?"; 
	$confirm_deluser_after = " ?";
	
	// Group deletion confirmation message. This message is in 2 parts in order to fill in with group name in the middle.
	$confirm_delgroup_before = "Desea realmente suprimir el grupo ? ";
	$confirm_delgroup_after  = " ?";
	
	// Error messages
	$err = array (
		"001" => "Login y contraseña requeridos",
		"002" => "Este login ya esta utilisado",
		"003" => "Nombre requerido",
		"004" => "Este grupo ya existe",
		"005" => "Error en la suprecíon del usuario",
		"006" => "Error en la suprecíon del grupo",
		"007" => "Error en la actualización del usuario",
		"008" => "Error en la actualización de los miembros del grupo",
		"009" => "Error en la actualización del logion del usuario",
		"010" => "Error en la actualización de la contraseña",
		"011" => "Login o contraseña incorrecta",
		"012" => "Acceso denegado. Autentificación requerida",
		"013" => "Error en la actualización del nombre del usuario",
		"014" => "Error en la actualización de la descripción",
		"015" => "Error en la actualización de la dirección mail",
		"016" => "El grupo admin debe contener AL MENOS 1 usuario.<br>Sin eso, ya no podreis conectar con la consola de administración",
		"017" => "No podeis suprimir el usuario con el que estaís conectado.<br>Reconectese con otro administrador.",
		"018" => "No podeis suprimir el actual usuario del grupo admin.<br>Reconectese con otro administrador.",
		"019" => "Aceso a esta zona no permitida. Derechos de acceso insufisientes."
	);
	
	// Information or confirmation messages
	$msg = array (
		"001" => "usuario creado",
		"002" => "Grupo creado",
		"003" => "usuario suprimido",
		"004" => "Grupo suprimido",
		"005" => "Groupo renombrado",
		"006" => "Actualisación de los miembros del grupo efectuada",
		"007" => "Actualisación del login del miembro efectuada",
		"008" => "Actualisación de la contraseña del usuario efectuada",
		"009" => "Modo demo. Operación prohibida", // user deletion
		"010" => "Modo demo. Operación prohibida", // group renaming
		"011" => "Modo demo. Operación prohibida", // group memberships update
		"012" => "Modo demo. Operación prohibida", // user creation
		"013" => "Modo demo. Operación prohibida", // group creation
		"014" => "Modo demo. Operación prohibida", // group deletion
		"015" => "Modo demo. Operación prohibida", // user login update
		"016" => "Modo demo. Operación prohibida", // user password modification
		"017" => "Actualisación del nombre de usuario efectuada",
		"018" => "Actualisación de la descripcion efectuada",
		"019" => "Modo demo. Operación prohibida", // group description update
		"020" => "Modo demo. Operación prohibida", // user name update
		"021" => "Modo demo. Operación prohibida", // user email update
		"022" => "Actualisación de la direccion mail efectuada"
	);
?>