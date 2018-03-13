<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

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
	$groupsArray = array("admin","supervisor");
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
	$pageTitle		= "Purchase Order Entry";
	
	$edit_record 	= $_GET['edit_record'];//this may not be used
	$remove_item 	= $_GET['remove_item'];
	$remove_po		= $_GET['remove_po'];
	$refer_page 	= $_SERVER['HTTP_REFERER'];
	
	$dbtable		= $db_purchase_order;				//used multiple times in other locations
	$primaryKey		= $database->getPrimaryKey($dbtable);
	
/*////////////////////////////////////////////////////////////////////////////////
Fields
///////////////////////////////////////////////////////////////////////////////*/
	
	// general format of Formelements is (column in table, label)
	$fPoNumber   	= new FormTextField ("po_number", "PO #");
	$fPoNumber->setReadOnly();
	$fStatus		= new FormSelect ("status", "Status", $po_status);
	$fDateOrdered	= new FormDateField ("date_ordered", "Date Ordered");
	$fShipDate		= new FormDateField ("est_ship_date", "Est Ship Date");
	$fAmount		= new FormTextField ("amount", "Amount");
	$fAcknowledged	= new FormCheckbox ("acknowledged", "Acknowledged");
	$fRequireQc		= new FormCheckbox ("require_qc", "QC required");
	
	//TODO: get vendor list from database from need select list with display=>value
	$vlist = array("Fake1"=>12345, "Fake2"=>45678);
	$fVendor		= new FormSelect ("vendor_id", "Vendor", $vlist);
	
	$fNotes			= new FormTextBox ("notes", "Notes");
	$fNotes->setSize(4, 35);
	
	$fDescription = new FormTextBox ("description", "Description");
	
	$fTerms			= new FormSelect ("payment_terms", "Terms", $terms_list);
	
	//TODO: get shipper list from database from need select list with display=>value
	$slist = array("Ship1"=>12345, "Ship2"=>45678);
	$fShipper		= new FormSelect ("shipper_id", "Shipper", $slist);
	
	$fTracking = new FormTextField ("tracking_no", "Tracking #");
	$fDescription = new FormHidden ("ordered_by", "Ordered By", $user);
	
	//Create form	
	$formObj = new FormHtml();
	$formObj->setTitle("PO Details");
	$formObj->setSuccessPage('employee_po_view.php');
	
	//set fields to display in form
	$fields = array($fPoNumber, $fVendor, $fStatus, $fDescription, $fDateOrdered, $fShipDate, $fAmount, 
					$fTerms, $fAcknowledged, $fRequireQc, $fShipper, $fTracking, $fNotes); //this is also the display order (TODO: maybe add groupings ie fieldset tag)
	$formObj->setFields($fields);
	
/*////////////////////////////////////////////////////////////////////////////////
Items
///////////////////////////////////////////////////////////////////////////////*/	
	
	//sets the fields for the individual items
	//Note [] is necessary for item fields to get input as an array
	$iQty		= new FormTextField ("Quantity[]", "Qty");
	$iPrice 	= new FormTextField ("UnitCost[]", "Unit Cost");
	$iDescription= new FormTextField ("Description[]", "Description");
	$iPart      = new FormTextField ("Part_No[]", "Part Numnber");

	$ifields = array($iQty, $iPrice, $iDescription, $iPart);

	$formObj->setItemFields($ifields);
	
	//get html code of form
	$formHtml = $formObj->htmlForm($fields);

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
		}
		else {
			$filter = '`'. $primaryKey . '` = '. $values[$primaryKey]; //goes in the WHERE of an SQL query
			$database->updateRecord($dbtable, $values, $filter);
		}
		
		//write item data.
		$po_number = $values['po_number'];
		$part_no   = $values['part_no'];
		$quantity  = $values['Quantity'];
		$sql = "DELETE FROM $db_purchase_order_items WHERE po_number='$po_number';";
		$retval = $database->query($sql);
		$i=0;
			while($i<count($part_no))
			{
			
				$sql = "INSERT INTO $db_purchase_order_items (
				 `po_number` ,
				 `part_no` ,
				 `Quantity`,
				 `UnitCost`
				)
				VALUES (
				 '$po_number',
				 '$part_no[$i]',
				 '$Quantity[$i]',
				 '$UnitCost[$i]'
				);";
	
				$retval = $database->query($sql);
				  $i++;
			}//end while
			
		
		//success message if submit successful
		echo $formObj->successHtml();		
		die();
	}//end if submit

/*////////////////////////////////////////////////////////////////////////////////
Edit Existing Record
///////////////////////////////////////////////////////////////////////////////*/

	$edit_record = $_REQUEST['edit_record'];

	if ($edit_record !=""){
		//read form date 
		$filter = '`'. $primaryKey  .'` = '. $edit_record;
		$formObj->setDefaults($database, $dbtable, $filter);
		$formHtml = $formObj->htmlForm($fields);
	}
	
	//get data for items
	//descriptions and details
	//read form date 
	$po_number = $fPoNumber->getDefaultValue();
	$sql = "SELECT * FROM $db_purchase_order_items WHERE `po_number` = '$po_number'";
	$retval = $database->query($sql);
		  while($row = $retval->fetch_assoc()){
		    $part_no[]		= $row['part_no'];
			$Quantity[]		= $row['quantity'];
			$UnitCost[]		= $row['UnitCost'];
		  }//end while

?>
<!DOCTYPE HTML>
<!--
	TXT 2.5 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title><?php echo $company_name;?></title>
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
										<h3><?php echo $pageTitle?> </h3>
									</header>
									<div id="menu">
										<ul>
										<li><a href="employee_po_view.php" ><span class="button-menu"><i class="fa fa-arrow-circle-o-left fa-fw"></i>&nbsp; View Purchase Orders</span></a></li>
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
