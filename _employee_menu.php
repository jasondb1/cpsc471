<?php
	//ini_set('display_errors',1); 
	//error_reporting(E_ALL);
	require('_config.php');
	require_once("phpauthent/phpauthent_core.php");
	require_once("phpauthent/phpauthent_config.php");
	require_once("phpauthent/phpauthentadmin/locale/".$phpauth_language);
	simplePageProtect();
	$user_id = getUserId();
	
?>
<!DOCTYPE HTML>
<!--
	TXT 2.5 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title><?php echo $company_name?></title>
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
					<div class="row">

						<div class="12u">
							
							<!-- Highlight -->
								<section class="is-highlight">

									<header>
										<h2>Employee Menu</h2>
										</header>
										<div id="menu">
											<ul>

											<form class="menuLayout" name="layout">
											<fieldset>
											<legend>Employee Time</legend>
											<li><a href="employee_time_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Timelog</span></a></li>
											</fieldset>

											<?php 	
											$groupsArray = array("admin","supervisor","sales","accounting");
											if (isEnabled(array(), $groupsArray)){ //display only those that are in groups
											?>			
											<fieldset>
											<legend>Sales</legend>
											<li><a href="employee_fixtures_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Fixtures</span></a></li>
											<li><a href="employee_sales_order_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Sales Order</span></a></li>
											<li><a href="employee_quote_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Quotes</span></a></li>
											<hr>
											<li><a href="employee_customer_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Customers</span></a></li>
											</fieldset>
											<?php } ?>
											
											
											<?php 	
											$groupsArray = array("admin","supervisor","engineering","operations","r_and_d");
											if (isEnabled(array(), $groupsArray)){ //display only those that are in group 
											?>	
											<fieldset>
											<legend>Engineering</legend>

											<li><a href="employee_components_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Components</span></a></li>
											<li><a href="employee_project_view.php"><span class="button-menu"><i class="fa fa-database fa-fw"></i>&nbsp; Projects</span></a></li>
											</fieldset>
											<?php } ?>
											
											<?php 	
											$groupsArray = array("admin","supervisor", "supply_chain", "accounting");
											if (isEnabled(array(), $groupsArray)){ //display only those that are in group 
											?>	
											<fieldset>
											<legend>Purchasing</legend>
											<li><a href="employee_po_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Purchase Order</span></a></li>
											<hr>
											<li><a href="employee_shipper_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Shippers</span></a></li>
											<li><a href="employee_vendor_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Vendors</span></a></li>
											</fieldset>
											<?php } ?>
											
											</form>

											</ul>
											</div>
										
								</section>
							<!-- /Highlight -->

						</div>
						
					</div>
					
					<div class="row">
						<div class="12u">

							<!-- Features -->
							
								
							<!-- /Features -->

						</div>
					</div>
	
				</div>
			</div>
		<!-- /Main -->

		<!-- Footer -->
			<footer id="footer" class="container">
				<div class="row">
					<div class="12u">

						<!-- Contact -->
						<a name="connect"></a>
							<section>
								<h2><span>Get in touch</span></h2>
								<? echo $company_street; ?><br>
								<? echo $company_city; ?>,<? echo $company_province; ?><br>
								<? echo $company_postal_code; ?><br>
								<br />
								P. <? echo $company_phone; ?><br />
								F. <? echo $company_fax; ?><br />
							</section>			
						<!-- /Contact -->
					
					</div>
				</div>

				<!-- Copyright -->
					<div id="copyright">
						&copy; <?php echo $company_name; ?> | Template: <a href="http://html5up.net/">HTML5 UP</a>
					</div>
				<!-- /Copyright -->

			</footer>
		<!-- /Footer -->

	</body>
</html>
