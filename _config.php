<?php
////////////////////////////////////////////////////////////////////////////
//
// _config.php
//
// Default values used for the website and database
//

require_once("phpauthent/phpauthent_core.php");
require_once("phpauthent/phpauthent_config.php");	
date_default_timezone_set('America/Edmonton');

/////Company Details
	$company_icon			= 'images/icon.png';	
	$company_name			= "Company Name";
	$company_street 		= "1234 Street";
	$company_city			= "Calgary";
	$company_province		= "Alberta";
	$company_postal_code	= "T2A 0A0";
	$company_phone			= "403.555.5555";
	$company_fax			= "403.555.5556";
	$company_wcb			= "1234567";
	$company_web			= "www.testcompany.ca";
	$company_country 		= "Canada";
	$company_domain			= "testcompany.ca";
	$company_description	= "";
	$company_keywords		= "test company";

// DB SETTINGS
	$dbhost 	= "localhost";	// Change this to the proper DB Host name
	$dbusername = "cpsc471"; 	// Change this to the proper DB User
	$dbpass 	= "testing";	// Change this to the proper DB User password
	$dbname		= "cpsc471"; 	// Change this to the proper DB Name
	
//jobfile database data
	$db_table_customers				= "Customers";
	$db_purchase_order				= "Purchase_Order";
		
	$db_table_jobfile 				= "jobdata";
	$db_employee					= "phpauthent_users";
	$db_customer 					= "customer";
	$db_timelog						= "timelog";
	$db_workorder					= "workorder";
	$db_purchase_order_items		= "purchase_order_items";
	$db_snowlog						= "snowlog";
	$db_changeorder					= "changeorder";
	$db_changeorder_items			= "changeorder_items";
	$db_quote						= "quote";
	$db_quote_items					= "quote_items";
	$db_request_for_information 	= "request_for_information";
	$db_folder_access 				= "folder_access";
	$db_equipment					= "equipment";
	$db_equipment_log				= "equipment_log";
	$db_equipment_temp				= "equipment_temp";

	$company_databases = array('jobdata'=>'Test Company');
	
//file settings
	$global_path			= '/files/';


//email addresses
	$email_office 			= "office@testcompany.ca";
	$email_service 			= "service@testcompany.ca";
	$email_accounting 		= "accounting@testcompany.ca";
	$email_payroll			= "payroll@testcompany.ca";
	$email_safety			= "safety@testcompany.ca";
	$email_boss 			= "boss@testcompany.ca";
	$email_sales			=	"sales@testcompany.ca";
	$email_hr				=	"hr@testcompany.ca";
	$email_other1			=	"";
	$email_other2			=	"";
	$email_other3			=	"";

//Other
// $permanent_jobs = array (
// 01001=>"Shop",
// 01003=>"Meeting",
// 01005=>"Snow Clearing",
// 01007=>"Training",
// 01009=>"Office",
// 01015=>"Use Banked Hours",
// 01019=>"Job Quoting",
// 01021=>"Shop - Formcrete",
// 01022=>"Safety Management",
// 01024=>"Cleaning Supplies")
// ;

//For select boxes array(display=>value)

$status_list= array(
"In Progress"=>"In Progress",
"Completed"=>"Completed",
"Recurring"=>"Recurring",
"On Hold"=>"On Hold",
"Pending"=>"Pending");

$terms_list= array(
"TBD"=>"TBD",
"Account"=>"Account",
"Cash"=>"Cash",
"Credit Card"=>"Credit Card",
"Net 15"=>"Net 15",
"Net 30"=>"Net 30",
"Net 45"=>"Net 45",
"Net 60"=>"Net 60"
);

$po_status= array(
"Ordered"=>"Ordered",
"Shipped"=>"Shipped",
"Received"=>"Received",
"Paid"=>"Paid",
"Complete"=>"Complete"
);

	
?>
