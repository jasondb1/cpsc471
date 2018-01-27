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
										<h2>Safety Menu</h2>
										</header>
										<div id="menu">
											<ul>

											<form class="menuLayout" name="layout">						
											<fieldset>
												<li><a href="safety_file_explorer.php"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Safety Files</span></a></li>
												<li><a href="http://work.alberta.ca/documents/whs-leg_ohsc_2009.pdf"><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; OH&S Code</span></a></li>
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
				<!-- Copyright -->
					<div id="copyright">
						&copy; <? echo $company_name; ?> | Template: <a href="http://html5up.net/">HTML5 UP</a>
					</div>
				<!-- /Copyright -->

			</footer>
		<!-- /Footer -->

	</body>
</html>