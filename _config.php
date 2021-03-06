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
	$dbhost 	= "localhost";			// Change this to the proper DB Host name
	$dbusername = "silverpi_cpsc471"; 	// Change this to the proper DB User
	$dbpass 	= "FTiBJ&@4^xK1";		// Change this to the proper DB User password
	$dbname		= "silverpi_cpsc471"; 	// Change this to the proper DB Name
	$db_employee					= "phpauthent_users";
	
//jobfile database data
	$db_table_customers				= "Customers";
	$db_purchase_order				= "Purchase_Order";
	$db_purchase_order_items		= "PO_Components";
	$db_table_temp					= "temp";
	$db_table_shipper				= "Shipper";
	$db_table_vendor				= "Vendor";
	$db_table_project				= "Project";
	$db_table_fixtures				= "Fixtures";
	$db_table_inventory				= "Inventory";
	$db_table_sales_order			= "Sales_Order";
	$db_table_timelog				= "Timelog";
	$db_table_quote					= "Quote";
	$db_table_quote_items			= "Items";
	$db_table_timelog				= "Timelog";
	$db_table_fixture_components	= "Fixture_Components";
	
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

$project_status= array(
"In Progress"=>"In Progress",
"On Hold"=>"On Hold",
"Pending"=>"Pending",
"Complete"=>"Complete"
);

$fixture_types= array(
"Indoor"=>"Indoor",
"Light Panel"=>"Light Panel",
"Outdoor"=>"Outdoor",
"Controller"=>"Controller"
);

$order_status= array(
"On Order"=>"On Order",
"Back Ordered"=>"Back Ordered",
"Shipped"=>"Shipped",
"Received"=>"Received",
"Paid"=>"Paid",
"Complete"=>"Complete"
);


?>
