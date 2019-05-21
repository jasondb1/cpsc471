<?php
//ini_set('display_errors',0); 
//error_reporting(0);
header("Cache-Control: private, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // A date in the past

/*////////////////////////////////////////////////////////////////////////////////
Includes
///////////////////////////////////////////////////////////////////////////////*/
	require('_config.php');
	require('Database.php');
	require('Table.php');
	require_once("phpauthent/phpauthent_core.php");
	require_once("phpauthent/phpauthent_config.php");
	require_once("phpauthent/phpauthentadmin/locale/".$phpauth_language);
	require ('_functions_common.php');
	
/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/
	$usersArray = array("administrator");
	$groupsArray = array("admin","supervisor","sales","accounting");//***
	pageProtect($usersArray,$groupsArray);

/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/	
	//get and set initial variables
		$database = new Database($dbhost, $dbname, $dbusername, $dbpass);
		
		$page_title="View Customers";								//***
		
		$dbtable		= $db_table_customers;						//***
		$primaryKey		= $database->getPrimaryKey($dbtable);

/*////////////////////////////////////////////////////////////////////////////////
Set View Options/ filter results of query
///////////////////////////////////////////////////////////////////////////////*/		
		
		if (isset($_GET['sort_by'])) {
			$orderBy = $_GET['sort_by'];
			$direction = $_GET['direction'];
			$page_rows = $_COOKIE['page_rows'];
		}
		else {
			$orderBy = "";
			$direction = "";
			$page_rows = "";
		}
		
		if (isset($_GET['search'])){
			$search = $_GET['search'];
		}
		else{
			$search = "";
		}

/*/////////////////////////////////////////////////
Delete Record
/////////////////////////////////////////////////*/
	if ($_GET['delete_record'] != ""){
		echo $database->deleteRecord($dbtable, "$primaryKey='". $_GET['delete_record'] ."'");
		die();	
	}//end if delete

/*/////////////////////////////////////////////////
//Create Table HTML
/////////////////////////////////////////////////*/
	
	//*** set all of the column information
	$columns = array(
		array('columnName'=>'CustomerID', 	'displayName'=>'CID', 			'type'=>'text'),
		array('columnName'=>'CompanyName',	'displayName'=>'Company', 		'type'=>'text'),
		array('columnName'=>'CFname', 		'displayName'=>'First Name', 	'type'=>'text'),
		array('columnName'=>'CLname', 		'displayName'=>'Last Name', 	'type'=>'text'),
		array('columnName'=>'Email', 		'displayName'=>'Email', 		'type'=>'text'),
		array('columnName'=>'Phone', 		'displayName'=>'Phone', 		'type'=>'text'),
		array('columnName'=>'Street', 		'displayName'=>'Street', 		'type'=>'text'),
		array('columnName'=>'City', 		'displayName'=>'City', 			'type'=>'text'),
		array('columnName'=>'Province', 	'displayName'=>'Province', 		'type'=>'text'),
		array('columnName'=>'Postal_Code', 	'displayName'=>'Postal Code', 	'type'=>'text')
	);

	//Search Criteria
	if ($search != ""){
		$i = 0;
		foreach ($columns as $row){
			if ($i == 0){ 
				$where = " " . $row['columnName'] . " LIKE '%$search%'";
			}
			else {
				$where .= " OR " . $row['columnName'] . " LIKE '%$search%'";
			}
			$i++;
		}	
	}
	else{
		$where =" true"; //default criteria
	}
		
	//new table object
	$table = new Table();
	$table->dataTable=$dbtable;
	$table->columns=$columns;
	
	$table->enableEdit=true;								//***
	$table->enableDelete=true;								//***
	$table->editPage="employee_customer_entry.php";			//***
	
	$table->orderByCol= $orderBy;
	$table->orderDirection = $direction;
	$table->filter = $where;
	$table->setdb($database);
	$tableHTML = $table->toHTML();		//gets the actual html code that is used below

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
					showWeek: true,
					firstDay: 1
					}
				);
				$( "#datepicker2" ).datepicker(
					{
					dateFormat: "yy-mm-dd",
					showButtonPanel: true,
					changeMonth: true,
					changeYear: true,
					showWeek: true,
					firstDay: 1
					}
				);
			});
		</script>
		<script type="text/javascript">
		<!--
			function toggle_visibility(id) {
			   var e = document.getElementById(id);
			   if(e.style.display == 'block')
				  e.style.display = 'none';
			   else
				  e.style.display = 'block';
			}
		//-->
		</script>
		<style type="text/css">
		  #datepicker1, #datepicker2{
			background-image:url("images/calendar.png");
			background-position:right center;
			background-repeat:no-repeat; }
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
				<section class="is-page-content">
					<div class="row flush" style="padding:0em; padding-top:2em;">
						<div class="12u">
							<header>
								<h3><?php echo $page_title; ?></h3>
							</header>
					
							<div id="menu">
								<ul>
								<li><a href="employee_customer_entry.php" ><span class="button-menu"><i class="fa fa-plus fa-fw"></i>&nbsp; Add Customer</span></a></li>
								</ul>
							</div>
							<hr>
						</div>
					</div>
					
					<div class="row flush" style="padding:0em;">
						<div class="9u" >
							&nbsp;
						</div>
						<div class="3u">
							<form style="background-color: rgb(255, 255, 255);" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="viewoptions">
								<?php  
									echo '<input class="text" type="text" name="search" placeholder="Search" value="'.$search. '">';
									echo '<input type="submit" name="submit" value="Search"/>';
								?>
							</form>
						</div>							
					</div>
					
					<hr>
					
					<div class="row flush" style="padding:0em;">
						<div class="12u">	
							<?php echo $tableHTML;?>
						</div>
					</div>							
											
				</section>
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
