<?php
	//ini_set('display_errors',1); 
	//error_reporting(E_ALL);
/*////////////////////////////////////////////////////////////////////////////////
Includes
///////////////////////////////////////////////////////////////////////////////*/
	require('_config.php');
	require_once("phpauthent/phpauthent_core.php");
	require_once("phpauthent/phpauthent_config.php");
	require_once("phpauthent/phpauthentadmin/locale/".$phpauth_language);
	
/*////////////////////////////////////////////////////////////////////////////////
Functions
///////////////////////////////////////////////////////////////////////////////*/


/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/	
//get and set initial variables
	$search=$_GET['search'];

	//connect to database
	//open database
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}

	//Get Data
		if ($search ==""){
		$where = " WHERE (((invoice_number IS NULL OR invoice_number='') AND status = 'Completed') OR status <> 'Completed')";
		}
		else{
				$where = " WHERE (((invoice_number IS NULL OR invoice_number='') AND status = 'Completed') OR status <> 'Completed')
				AND (jobnumber LIKE '%$search%'
				OR description LIKE '%$search%'
				OR location LIKE '%$search%'
				OR customer LIKE '%$search%'
				)
				";
		}	
		$order = " ORDER BY jobnumber DESC";
		
		$table="";
		foreach ($company_databases as $key=>$value){	
			$sql = "SELECT jobnumber,customer,description,location,require_div,require_subdiv FROM $key". $where .$order;
			//echo $sql;
			$result = $mysql_link->query($sql);
			$table .= "<b>$value Jobs</b><br><hr>";
			//output table
			//display jobs and linke to time entry sheet	  
			while($row = $result->fetch_assoc()){
			  $jobnumber = $row['jobnumber'];
			  $description = $row['description'];
			  $location = $row['location'];
			  $customer = $row['customer'];
			  $require_division = $row['require_div'];
			  $require_subdivision = $row['require_subdiv'];
				$table .= "<a href=\"employee_po_entry.php?edit_record=$jobnumber\" ><b>$jobnumber</b>: $location - $description</a><br><hr>";
			}	//end while
			$table .= "<br><hr>";
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
		<title><? echo $company_name?></title>
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
										<h3>Purchase Order - Select Job</h3>
									</header>
									<div id="menu">
										<ul>
										<li><a href="employee_po_view.php" ><span class="button-menu"><i class="fa fa-arrow-circle-o-left fa-fw"></i>&nbsp; Purchase Orders</span></a></li>
										</ul>
									</div>
								</div>
							</div>
							<!-- /Menu Buttons -->
							<!-- Form -->
							<div class="row flush" style="padding:0em;">					
								<div class="12u">
									<hr>
									<span class="hangingHead">Purchase Order</span><br>
									<!-- Table Controls/Options -->
									<form method="get" action="<?php $_SERVER['PHP_SELF'] ?>" name="options">
									<?php 
										echo '<input style="background-color:#FFFFCC; border-style:solid; border-width:medium;" type="text" name="search" placeholder="Search" value="'.$search. '">';
										echo '<input type="submit" name="submit" value="Search"/>';
									?>
									 </form>
									 <!-- /Table Controls/Options -->
									  <div style="background-color:#f4eebe; margin:10px; border:1px solid black;">
										  <?php	echo $table; ?>
									</div>

					
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
				<!-- Copyright -->
					<div id="copyright">
						&copy; <? echo $company_name; ?> | Template: <a href="http://html5up.net/">HTML5 UP</a>
					</div>
				<!-- /Copyright -->
	</body>
</html>