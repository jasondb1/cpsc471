<?php
//ini_set('display_errors',1);
//error_reporting(E_ALL);

/*////////////////////////////////////////////////////////////////////////////////
Includes
///////////////////////////////////////////////////////////////////////////////*/
	
	require('_config.php');
	require('Database.php');
	require('FormHtml.php');
	require_once("phpauthent/phpauthent_core.php");
	require_once("phpauthent/phpauthent_config.php");
	require_once("phpauthent/phpauthentadmin/locale/".$phpauth_language);
	require ('_functions_common.php');
	
/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/	
	$usersArray  = array();
	$groupsArray = array("admin","supervisor","sales","accounting");
	pageProtect($usersArray,$groupsArray);	

/*////////////////////////////////////////////////////////////////////////////////
Functions
///////////////////////////////////////////////////////////////////////////////*/

	//new Database object
	$database = new Database($dbhost, $dbname, $dbusername, $dbpass);
		
/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/

//*CHANGE VALUES IN THIS SECTION

	//get and set initial variables
	$user 			= trim(getUsername());
	$page_title		= "Quote Entry";					//***
	
	$dbtable		= $db_table_quote;				//used multiple times in other locations
	$primaryKey		= $database->getPrimaryKey($dbtable);
	$itemKey		= $database->getPrimaryKey("Items");
	
		if (isset($_GET['edit_record'])) {
			$edit_record 	= $_GET['edit_record'];
		}
		if (isset($_GET['remove_item'])) {
			$remove_item	= $_GET['remove_item'];				
		}
		if (isset($_GET['remove_po'])) {
			$remove_po	= $_GET['remove_po'];				//***
		}
		$refer_page 	= $_SERVER['HTTP_REFERER'];

	
/*////////////////////////////////////////////////////////////////////////////////
Remove Item
///////////////////////////////////////////////////////////////////////////////*/

///add code for remove item
if ($remove_item!=""){
$refer_page 	= $_GET['refer_page'];
		$sql = "DELETE FROM $db_table_quote_items WHERE Id='$remove_item'";
	$database->query($sql);
		
	$message = "<br><b><span style=\"padding-left:2em;color:#f00;\"><big>Item Removed</big></span></b><br>";

}	
	
	
/*////////////////////////////////////////////////////////////////////////////////
Fields
///////////////////////////////////////////////////////////////////////////////*/
	
	//***
	// general format of Formelements is (column in table, label)
	$fQuoteNumber   	= new FormTextField ("QuoteNo", "Quote #", $dbtable);
	$fQuoteNumber->setReadOnly();
	//$fStatus		= new FormSelect ("status", "Status", $po_status, $dbtable);
	$fDateOrdered	= new FormDateField ("date_created", "Date Ordered", $dbtable);
	$fShipDate		= new FormDateField ("est_ship_date", "Est Ship Date", $dbtable);
	$fAmount		= new FormTextField ("amount", "Amount", $dbtable);
	
	$customer_list = $database->getListAssoc("Customers", "CompanyName", "CustomerID", "1", true);

	$fCustomer		= new FormSelect ("CustomerID", "Customer", $customer_list, $dbtable);
	
	$fNotes			= new FormTextBox ("notes", "Notes", $dbtable);
	$fNotes->setSize(4, 35);
	
	$fDescription = new FormTextField ("Description", "Description", $dbtable);
	
	$fTerms			= new FormSelect ("Terms", "Terms", $terms_list, $dbtable);
	
	//$shipper_list = $database->getListAssoc("Shipper", "Shname", "ShipperID", "1", true);
	//$fShipper		= new FormSelect ("shipper_id", "Shipper", $shipper_list, $dbtable);
	
	$employee_list = getEmployeeNames();
	$fSalesPerson = new FormSelect ("SalesPersonID", "Sold By", $employee_list, $dbtable);
	
	//Create form	
	$formObj = new FormHtml();
	$formObj->setTitle("Quote Details");							//***
	$formObj->setSuccessPage('employee_quote_view.php');			//***
					
	//*** set fields and groups to display in form
	$group_1 = array($fQuoteNumber, $fCustomer, $fDescription, $fDateOrdered, $fShipDate, $fAmount);
	$group_2 = array($fTerms, $fSalesPerson);
	$group_3 = array($fNotes);
	$groups = array('Details'=>$group_1, 'Shipping Details'=>$group_2, 'Receiving Details'=>$group_3);	//Groups are array(Name=>array(fields))
	
	//get html code of form
	$formObj->setGroups($groups);	
	
/*////////////////////////////////////////////////////////////////////////////////
Items
///////////////////////////////////////////////////////////////////////////////*/	
	
	//sets the fields for the individual items						/***
	$iQty		= new FormTextField ("Quantity", "Quantity", "Items");
	$iQty->setIsMultiItem();
	$iPrice 	= new FormTextField ("Price", "Unit Price", "Items");
	$iPrice->setIsMultiItem();
	
	$part_list = getPartList();
	$iPart      = new FormSelect ("part_no", "Part Number", $part_list, "Items");
	$iPart->setIsMultiItem();

	//$ifields = array($iQty, $iPrice, $iDescription, $iPart);
	$ifields = array($iPart, $iQty, $iPrice);

	$formObj->setItemFields($ifields);
	$itemHtml = $formObj->htmlItemFields();
	
	//get html code of form
	$formHtml = $formObj->htmlForm($itemHtml);

/*////////////////////////////////////////////////////////////////////////////////
Process Form
///////////////////////////////////////////////////////////////////////////////*/
	if (isset($_POST['submit'])){
	
		//sanitize against sql injections
		$_POST = sanitize($_POST);		

		//get $_Post values into associated array
		$values = $formObj->getData($_POST);
	
		//Data Validation - message on error and die 
		
//*CHANGE THESE VALUES AS REQUIRED
		if($values['amount'] == ""){
			echo $formObj->failureHtml();		
			die();
		}
		 
		//Write Records to database or edit records
		//Set Primary Key in Variable Section and do not change table here
		if ($values[$primaryKey] == ""){
			$database->newRecord($dbtable, $values);
			
		////////////////////////////////////only for items
			//get purchase order id
			$record_id = $database->getlink()->insert_id;
			
			//get po number
			$sql = "SELECT QuoteNo FROM $dbtable WHERE QuoteNo = '$record_id';";	//***
			$retval = $database->query($sql);
			$id_number = $retval->fetch_row();
			$id_number = $id_number[0];
		}
		else {
			$filter = '`'. $primaryKey . '` = \''. $values[$primaryKey] . '\''; //goes in the WHERE of an SQL query
			$database->updateRecord($dbtable, $values, $filter);
			$id_number = $values['QuoteNo'];											//***
		}
		

///////////////////////////////////Items /***	
		//TODO: Move this to FormHtml to clean this up	
		//write item data.
		$quote_number = $id_number;
		$part_no   = $_POST['part_no'];
		$Quantity  = $_POST['Quantity'];
		$Price  = $_POST['Price'];
		$sql = "DELETE FROM $db_table_quote_items WHERE QuoteNo='$quote_number';";
		$retval = $database->query($sql);
		$i=0;
		
			while($i<count($part_no))
			{
			
				$sql = "INSERT INTO $db_table_quote_items (
				 `QuoteNo` ,
				 `part_no` ,
				 `Quantity`,
				 `Price`
				)
				VALUES (
				 '$quote_number',
				 '$part_no[$i]',
				 '$Quantity[$i]',
				 '$Price[$i]'
				);";
				$retval = $database->query($sql);
				  $i++;
			}//end while
/////////////////////////////////////			
		
		//success message if submit successful
		echo $formObj->successHtml();		
		die();
	}//end if submit

/*////////////////////////////////////////////////////////////////////////////////
Edit Existing Record
///////////////////////////////////////////////////////////////////////////////*/

	$edit_record = $_REQUEST['edit_record'];

	if ($edit_record !=""){
		//read form data 
		$filter = '`'. $primaryKey  .'` = '. $edit_record;
		$formObj->setDefaults($database, $dbtable, $filter);


		//get po_number
		$quote_number = $fQuoteNumber->getDefaultValue();
		//get data for items
		$sql = "SELECT * FROM $db_table_quote_items WHERE `QuoteNo` = '$quote_number'";
		
		$formObj->setItemFields($ifields);
		
		$itemHtml = $formObj->htmlEditItemFields($database, $sql, $itemKey, "", $edit_record);
		
		$formHtml = $formObj->htmlForm($itemHtml);
	}

?>
<!DOCTYPE HTML>
<!--
	TXT 2.5 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title><?php echo $page_title;?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="<?php echo $company_description ?>" />
		<meta name="keywords" content="<?php echo $company_keywords ?>" />
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700|Open+Sans+Condensed:700" rel="stylesheet" />
		<script src="js/jquery.min.js"></script>
		<script src="js/config.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-panels.min.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-desktop.css" />
			<link rel="stylesheet" href="css/font-awesome.css" />
		</noscript>

		<link rel="stylesheet" href="css/jquery-ui.min.css">
		<link rel="stylesheet" href="css/jquery-ui.theme.min.css">
		<link rel="stylesheet" href="css/jquery-ui.structure.min.css">
		<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">

		<script src="js/jquery-ui.min.js"></script>
		<script src="js/jquery-ui-timepicker-addon.js"></script>
		<script>
			$(function() {
				$( "#datepicker1" ).datepicker(
					{
					dateFormat: "yy-mm-dd",
					showButtonPanel: true,
					changeMonth: true,
					changeYear: true,
					}
				);
			});
		</script>	
		<style type="text/css">
		  #datepicker1, #timepicker1, #timepicker2{
			background-position:right center;
			background-repeat:no-repeat; }
		   #timepicker1, #timepicker2 {background-image:url("images/clock.png");}
		   #datepicker1 {background-image:url("images/calendar.png");}
		</style>

		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
		<!--[if lte IE 7]><link rel="stylesheet" href="css/ie7.css" /><![endif]-->
	</head>
	<body class="homepage">

		<!-- Header -->
			<?php //include('_header.php');  ?>
		<!-- /Header -->

		<!-- Nav -->
			<?php include('_menu_employee.php');  ?>
		<!-- /Nav -->

		<!-- Banner -->
		<!-- /Banner -->

		<!-- Main -->
			<div id="main-wrapper">
				<div id="main" class="container">
					<!-- Page Content -->
					<div class="row">
						<div class="12u">
						<section class="is-page-content">
							<!-- Menu Buttons -->
							<div class="row flush" style="padding:0em; padding-top:2em;">
								<div class="12u">
									<header>
										<?php $message; ?>
										<h3><?php echo $page_title?> </h3>
									</header>
									<div id="menu">
										<ul>
										<li><a href="employee_quote_view.php" ><span class="button-menu"><i class="fa fa-arrow-circle-o-left fa-fw"></i>&nbsp; View Quotes</span></a></li>
										</ul>
									</div>
								</div>
							</div>
							<!-- /Menu Buttons -->
							<!-- Form -->
							<div class="row flush" style="padding:0em;">
								<div class="12u">
									<hr>
									<?php echo $formHtml; ?>
								</div>
							</div>
							<!-- /Form -->
						</section>
				<!-- /Page Content -->

						</div>
					</div>
				</div>
			</div>
		<!-- /Main -->
		<!-- Footer -->
			<footer id="footer" class="container">
				<!-- Copyright -->
				<div id="copyright">
					&copy; <?php echo $company_name; ?> | Template: <a href="http://html5up.net/">HTML5 UP</a>
				</div>
				<!-- /Copyright -->
			</footer>
		<!-- /Footer -->
	</body>
</html>
