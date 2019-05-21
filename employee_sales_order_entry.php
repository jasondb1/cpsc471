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
	$groupsArray = array("admin","supervisor","sales","accounting");					//***
	pageProtect($usersArray,$groupsArray);	

/*////////////////////////////////////////////////////////////////////////////////
Functions
///////////////////////////////////////////////////////////////////////////////*/

	//new Database object
	$database = new Database($dbhost, $dbname, $dbusername, $dbpass);
		
/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/

	//get and set initial variables
	$user 			= trim(getUsername());
	$page_title		= "Order Data";							//***
	
	$dbtable		= $db_table_sales_order;								//***
	$primaryKey		= $database->getPrimaryKey($dbtable);
	
	//*** general format of Formelements is (column in table, label, default value)
	$fUid   	= new FormHidden ("uid", "UID", $dbtable);
	$fUid->setReadOnly();
	$fOrderNumber		= new FormTextField ("SalesOrderNo", "Order No", $dbtable);
	$fOrderNumber->setReadOnly();
	$quote_list = $database->getListAssoc("Quote", "QuoteNo", "QuoteNo", "1", true);
	$fQuote		= new FormSelect ("QuoteNo", "QuoteNo", $quote_list, $dbtable);
	
	$shipper_list = $database->getListAssoc("Shipper", "Shname", "ShipperID", "1", true);
	$fShipper		= new FormSelect ("shipper_id", "Shipper", $shipper_list, $dbtable);

	$fDateRecvd			= new FormDateField ("date_received", "Date Recv'd", $dbtable);
	$fAmount		= new FormTextField ("amount", "Amount", $dbtable);
	$fFreight		= new FormTextField ("freight_charge", "Freight", $dbtable);
	$fDateShip		= new FormDateField ("est_ship_date", "Est. Ship", $dbtable);
	$fTrackingNo		= new FormTextField ("tracking_no", "Tracking #", $dbtable);
	$fDateCreate			= new FormDateField ("date_created", "Created On", $dbtable);
	$fStatus		= new FormSelect ("status", "Status", $order_status, $dbtable);
	
	$shipper_list = $database->getListAssoc("Shipper", "Shname", "ShipperID", "1", true);
	$fShipper		= new FormSelect ("shipper_id", "Shipper", $shipper_list, $dbtable);
	
	//Create form	
	$formObj = new FormHtml();
	
	$formObj->setTitle("Order Details");						//***
	$formObj->setSuccessPage('employee_sales_order_view.php');		//***

	//*** set fields and groups to display in form
	$group_1 = array($fUid, $fOrderNumber, $fStatus);
	$group_2 = array( $fQuote, $fDateCreate, $fAmount, $fFreight);
	$group_3 = array ($fShipper, $fTrackingNo, $fDateShip, $fDateRecvd);
	$groups = array('Information'=>$group_1, 'Details'=>$group_2, 'Shipping Details'=>$group_3);	//Groups are array(Name=>array(fields))
	
	//get html code of form
	$formObj->setGroups($groups);
	
	//get html code of form
	$formHtml = $formObj->htmlForm($groups);

/*////////////////////////////////////////////////////////////////////////////////
Process Form
///////////////////////////////////////////////////////////////////////////////*/
	if (isset($_POST['submit'])){
	
		//sanitize against sql injections
		$_POST = sanitize($_POST);

		//get $_Post values into associated array
		$values = $formObj->getData($_POST);
	
		//Data Validation - message on error and die 
		//if($values['CFname'] == "" || $values['CLname'] ==""){		//***
		//	echo $formObj->failureHtml();		
		//	die();
		//}
		 
		//Write Records to database or edit records
		//Set Primary Key in Variable Section and do not change table here
		if ($values[$primaryKey] == ""){
			$database->newRecord($dbtable, $values);
		}
		else {
			$filter = '`'. $primaryKey . '` = '. $values[$primaryKey]; //goes in the WHERE of an SQL query
			$database->updateRecord($dbtable, $values, $filter);
		}	
		
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
		$formHtml = $formObj->htmlForm($groups);
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
		<title><?php echo $page_title?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="<? echo $company_description ?>" />
		<meta name="keywords" content="<? echo $company_keywords ?>" />
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
										<h3><?PHP echo $page_title?> </h3>
									</header>
									<div id="menu">
										<ul>
										<li><a href="employee_sales_order_view.php" ><span class="button-menu"><i class="fa fa-arrow-circle-o-left fa-fw"></i>&nbsp; View Sales Orders</span></a></li>
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
