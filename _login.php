<?php
	//ini_set('display_errors',1); 
	//error_reporting(E_ALL);	
	require_once("phpauthent/phpauthent_core.php");
	require_once("phpauthent/phpauthent_config.php");
	include_once('_config.php')
?>
<!DOCTYPE HTML>
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
			<?php include('_header.php');  ?>
		<!-- /Header -->

		<!-- Nav -->
			<?php include('_menu_main.php');  ?>
		<!-- /Nav -->
		
		<?php
			if (isset($_GET['err']) && ($_GET['err'] == '011')) {
				print "<p><strong>Invalid username or password</strong></p>";
			}
			if (isset($_GET['err']) && ($_GET['err'] == '012')) {
				print "<p><strong>Access denied. Authentication required</strong></p>";
			}
			if (isset($_GET['err']) && ($_GET['err'] == '019')) {
				print "<p><strong>Access denied. You do not have necessary authorizations</strong></p>";
			}
		?>
		
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
										<h2>Login</h2>
										</header>
										<?php
											if (isset($_GET['err']) && ($_GET['err'] == '011')) {
												print "<p><strong>Invalid username or password</strong></p>";
											}
											if (isset($_GET['err']) && ($_GET['err'] == '012')) {
												print "<p><strong>Access denied. Authentication required</strong></p>";
											}
											if (isset($_GET['err']) && ($_GET['err'] == '019')) {
												print "<p><strong>Access denied. You do not have necessary authorizations</strong></p>";
											}
										?>
										<form name="loginform" method="post" action="<?php echo $phpauth_loginform_action;?>">
											<fieldset>
												<legend>Login</legend>
												<input class="text" name="<?php echo $phpauth_loginform_username; ?>" type="text" id="username" placeholder="Username">								
												<input class="text" name="<?php echo $phpauth_loginform_password; ?>" type="password" id="password" placeholder="Password">
												<input class="submit" type="submit" name="Submit" value="Submit">
												<input type="reset" name="Reset" value="Reset">
											</fieldset>
										</form>
										<p>&nbsp;</p>
										
										<!-- /Forgot Password -->
										
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
