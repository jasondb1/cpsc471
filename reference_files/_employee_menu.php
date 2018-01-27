<?php
	//ini_set('display_errors',1); 
	//error_reporting(E_ALL);
	require('_config.php');
	require_once("phpauthent/phpauthent_core.php");
	require_once("phpauthent/phpauthent_config.php");
	require_once("phpauthent/phpauthentadmin/locale/".$phpauth_language);
	simplePageProtect();
	
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
											<legend>Time</legend>
											<li><a href="employee_time_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Timelog</span></a></li>
											<li><a href="employee_snow_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Snow Log</span></a></li>
											</fieldset>
											<fieldset>
											<legend>Job Functions</legend>
											<li><a href="employee_job_view.php"><span class="button-menu"><i class="fa fa-database fa-fw"></i>&nbsp; Database</span></a></li>
											<hr>
											<li><a href="employee_po_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Purchase Order</span></a></li>
											<!--<li><a href="employee_change_order_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Change Orders</span></a></li>-->
											<li><a href="employee_workorder_select_job.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Work Orders</span></a></li>
											<!--<li><a href="employee_quote_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Quotes</span></a></li>-->
											<!--<li><a href="employee_rfi_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; RFI View</span></a></li>-->
											<li><a href="employee_invoice_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Vendor Invoices</span></a></li>
											<!--<li><a href="protected_folder_view.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Drawings and Reference</span></a></li>-->
											</fieldset>
											
											<fieldset>
											<legend>Safety Menu</legend>
											<li><a href="_safety_menu.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Safety Menu</span></a></li>
											</fieldset>
											<!--<fieldset>
											<legend>Safety</legend>
											<li><a href="safety_file_explorer.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Safety Files</span></a></li>
											<li><a href="employee_hazard_snow.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Snow Hazard Analysis - Hand Shovelling</span></a></li>
											<li><a href="employee_hazard_snow_machine.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Snow Hazard Analysis - Machine Operator</span></a></li>
											</fieldset>-->
											<!--<fieldset>
											<legend>Admin</legend>
											<li><a href="employee_menu_administration.php" ><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Administration Menu >>></span></a></li>
											<li><a href="file_explorer.php" ><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Document Explorer</span></a></li>
											<li><a href="m_employee_vacation_request.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Vacation Request</span></a></li>
											</fieldset>-->
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
						&copy; <? echo $company_name; ?> | Template: <a href="http://html5up.net/">HTML5 UP</a>
					</div>
				<!-- /Copyright -->

			</footer>
		<!-- /Footer -->

	</body>
</html>