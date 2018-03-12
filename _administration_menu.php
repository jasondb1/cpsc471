<?php
	//ini_set('display_errors',1); 
	//error_reporting(E_ALL);
	require('_config.php');
	require_once("phpauthent/phpauthent_core.php");
	require_once("phpauthent/phpauthent_config.php");
	$usersArray = array("administrator");
	$groupsArray = array("admin");
	pageProtect($usersArray,$groupsArray);
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
													<legend>Users</legend>
													<li><a href="phpauthent/phpauthentadmin/" ><span class="button-menu"><i class="fa fa-users fa-fw"></i>&nbsp; Users</span></a></li>
													<li><a href="phpmyadmin/" ><span class="button-menu"><i class="fa fa-users fa-fw"></i>&nbsp; phpmyadmin</span></a></li>
												</fieldset>
												<fieldset>
													<legend>Other Information</legend>
													<li><a href="admin_snow_list.php" ><span class="button-menu"><i class="fa fa-database fa-fw"></i>&nbsp; Edit Snow List</span></a></li>
												</fieldset>
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
								<?php echo $company_street; ?><br>
								<?php echo $company_city; ?>,<?php echo $company_province; ?><br>
								<?php echo $company_postal_code; ?><br>
								<br />
								P. <?php echo $company_phone; ?><br />
								F. <?php echo $company_fax; ?><br />
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
