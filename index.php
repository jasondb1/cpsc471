<?php 
	//ini_set('display_errors',1); 
	//error_reporting(E_ALL);
require_once("phpauthent/phpauthent_core.php");
require_once("phpauthent/phpauthent_config.php");
require_once("phpauthent/phpauthentadmin/locale/".$phpauth_language);
require('_config.php'); 

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
		<p>
		  <?php
			if (isset($_GET['err'])) {
				$err_idx = $_GET['err'];
				print "<p class=\"txtError\">".$err[$err_idx]."</p>";
			}
		?>
		  <?php
			if (isset($_GET['msg'])) {
				$msg_idx = $_GET['msg'];
				print "<p class=\"txtMessage\">".$msg[$msg_idx]."</p>";
			}
		?>
		</p>
		<!-- Header -->
			<?php include('_header.php');  ?>
		<!-- /Header -->

		
		
		<!-- Nav -->
			<?php include('_menu_main.php');  ?>
		<!-- /Nav -->
		
		<!-- Banner -->
			<div id="banner-wrapper">
				<section id="banner">
					<h2><?php $company_name; ?></h2>
					<span class="byline">[Byline Here]</span>
					<a href="#about" class="button">See What We Offer</a>
				</section>
			</div>
								<a name="about"></a>
		<!-- /Banner -->

		<!-- Main -->
			<div id="main-wrapper">
				<div id="main" class="container">
					<div class="row">

						<div class="12u">
							
							<!-- Highlight -->
								<section class="is-highlight">

									<header>
										<h2>Who We Are</h2>

									</header>
									<p>
										Company Introduction Here<br />
										
									</p>
								</section>
							<!-- /Highlight -->

						</div>
						
					</div>
					<a name="about"></a>
					<div class="row">
						<div class="12u">

							<!-- Features -->
							
								<section class="is-features">
									<h2 class="major"><span>What We Do</span></h2>
									<div>
										<div class="row">
											<div class="4u -2u">
												
												<!-- Feature -->
													<section class="is-feature">
														
														<ul class="special">
															<li><a href="#" class="fa fa-users solo"><span>Commercial</span></a></li>
														</ul>
														<h3><a href="#">Projects</a></h3>
														<p>
														What we can do for you.
														</p>
														
													</section>
												<!-- /Feature -->
										
											</div>
											
											<div class="4u">
												
												<!-- Feature -->
													<section class="is-feature">
														
														<ul class="special">
															<li><a href="#" class="fa fa-wrench solo"><span>Service</span></a></li>
														</ul>
														<h3><a href="#">Service</a></h3>
														<p>
														Our Service options
														</p>
													
													</section>
													
												<!-- /Feature -->
										
											</div>
										</div>

	
									</div>
								</section>
							<!-- /Features -->
							
								<section class="is-features">
									<h2 class="major"><span>We also do...</span></h2>
									
									<div>
										<div class="row">
											<div class="12u">
												
												<!-- Feature -->
													<section class="is-feature">
														
														<ul class="special">
															<li><a href="#" class="fa fa-car solo"><span>Curb Stops and Ramps</span></a></li>
															
														</ul>
														<h3><a href="curbramps.php">Secondary Activities</a></h3>
														<p>
														Description of secondary activities
														</p>
														
													</section>
												<!-- /Feature -->
										
											</div>
											
										</div>
									</div>
								</section>
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
								<?php echo $company_city; ?>,<? echo $company_province; ?><br>
								<?php echo $company_postal_code; ?><br>
								<br />
								P. <?php echo $company_phone; ?><br />
								F. <?php echo $company_fax; ?><br />
							</section>			
						<!-- /Contact -->
					
					</div>
				</div>
				
				<div class="row">
					<div class="12u">
						<section>
							<form method="post" action="connect.php">
									<fieldset>
										<legend><h3>Connect With Us</h3></legend>
													<textarea id="message" name="message" rows="8" cols="45" Placeholder="Message..."></textarea>
													<input class="text" id="Name" type="text" name="name" placeholder="Name" size="30">
													<input class="text" id="Email" type="email" name="email" placeholder="Email" size="40">	
													<input class="submit" id="submit" type="submit" name="submit" value="Submit"  />
									</fieldset>
								</form>	
							</section>
					</div>
				</div>

				<!-- Copyright -->
					<div id="copyright">
						&copy; <?php echo "$company_name"; ?> | Template: <a href="http://html5up.net/">HTML5 UP</a>
					</div>
				<!-- /Copyright -->

			</footer>
		<!-- /Footer -->

	</body>
</html>
