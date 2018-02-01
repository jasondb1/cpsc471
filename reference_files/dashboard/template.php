<?php
require_once("../phpauthent/phpauthent_core.php");
require_once("../phpauthent/phpauthent_config.php");

/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/
	$usersArray = array("administrator");
	$groupsArray = array("admin","employee","payroll","supervisor");
	pageProtect($usersArray,$groupsArray);

	$jobnumber=$_POST['jobnumber'];
	if ($jobnumber==""){ $jobnumber=$_COOKIE['jobnumber']; }
	setcookie("jobnumber",$jobnumber);
	//include_once ("auth.php");
	//include_once ("authconfig.php");
	//include_once ("check.php");
	include ("cfg_dashboard.php");
	include_once ("functions_dashboard.php");

	$masked_jobnumber= xor_this($jobnumber);
	//echo $masked;
	//echo xor_this($masked);
	 
?>

<!DOCTYPE HTML>
<!--
	Twenty by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Project Dashboard</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	</head>
	<body class="index">
		<div id="page-wrapper">

						<!-- Header -->
			<?php include ("header.php");?>

			<!-- Banner -->
				<section id="banner_small">

					<!--
						".inner" is set up as an inline-block so it automatically expands
						in both directions to fit whatever's inside it. This means it won't
						automatically wrap lines, so be sure to use line breaks where
						appropriate (<br />).
					-->
					<div class="inner">

						<header>
							<h2>Employee Dashboard</h2>
						</header>
						<?php echo $jobselect;?>
					</div>

				</section>

			<!-- Main -->
				<article id="main">


					<!-- Three -->
						<section class="wrapper style3 container special">			
<!-- //Row 1 -->							
							<div class="row">
								<div class="6u 12u(narrower)">

									<section class="special_table" >
										<header>
											<h3>Info Summary</h3>
										</header>
										<?php echo $jobinfo;?>
									</section>

								</div>
								<div class="6u 12u(narrower)">
									<section class="special_table">
										<header>
											<h3><a href="view_files.php?j=<?php echo $masked_jobnumber; ?>">Project Files</a></h3>
										</header>											
									
										<div style="overflow-y: auto; height:200px;" >
											<?php echo $files_table; ?>
										</div>
									</section>
								</div>
							</div>
<!-- //Row 2 -->							
							<div class="row">
								<div class="6u 12u(narrower)">

									<section class="special_table">
										<header class="special_table">
											<h3><a href="view_itb.php?j=<?php echo $masked_jobnumber; ?>">Invitation To Bid</a></h3>
										</header>											
									
										<div style="overflow-y: auto; height:200px;" >
												<?php echo $itb_table; ?>
										</div>
									</section>

								</div>
								<div class="6u 12u(narrower)">

									<section class="special_table">
										<header>
											<h3><a href="view_change_orders.php?j=<?php echo $masked_jobnumber; ?>">Change Orders</a></h3>
										</header>
										<div style="overflow-y: auto; height:200px;" >
											<?php echo $co_table; ?>
										</div>
									</section>

								</div>
							</div>
<!-- //Row 3 -->							
							<div class="row">
								<div class="6u 12u(narrower)">

									<section class="special_table">
										<header>
											<h3><a href="view_rfi.php?j=<?php echo $masked_jobnumber; ?>">Request For Information</a></h3>
										</header>											
									
										<div style="overflow-y: auto; height:200px;" >
											<?php echo $rfi_table; ?>
										</div>
									</section>

								</div>
								<div class="6u 12u(narrower)">

									<section class="special_table">
										<header>
											<h3><a href="view_site_instruction.php?j=<?php echo $masked_jobnumber; ?>">Site Instructions</a></h3>
										</header>
										<div style="overflow-y: auto; height:200px;" >
											<?php echo $si_table; ?>
										</div>
									</section>

								</div>
							</div>
<!-- //Row 4 -->							
							<div class="row">
								<div class="6u 12u(narrower)">

									<section class="special_table">
										<header>
											<h3>
												<a href="view_purchase_orders.php?j=<?php echo $masked_jobnumber; ?>">Purchase Orders</a>
												<div class="right">
													<div class="align-right">
														Total: <?php echo money_format("%n",$po_amount);?>
													</div>
												</div>
											</h3>
										</header>											
									
										<div style="overflow-y: auto; height:200px;" >
											<?php echo $po_table; ?>
										</div>
									</section>

								</div>
								<div class="6u 12u(narrower)">

									<section class="special_table">
										<header>
											<h3>
												<a href="view_vendor_invoices.php?j=<?php echo $masked_jobnumber; ?>">Vendor Invoices</a>
												<div class="right">
													<div class="align-right">
														Total: <?php echo money_format("%n",$total_amount);?>
													</div>
												</div>
											</h3>
										</header>
										<div style="overflow-y: auto; height:200px;" >
											<?php echo $inv_table; ?>
										</div>
									</section>

								</div>
							</div>
<!-- //Row 5 -->							
							<div class="row">
								<div class="6u 12u(narrower)">


								<section class="special_table">
										<header>
											<h3>Daily Reports</h3>
										</header>
										<ul>
										<li>Under Construction</li>
										<li>Report 2</li>
										<li>Report 3</li>
										<li>Report 4</li>
										<li>Report 5</li>
										<li>Report 6</li>
										<li>Report 7</li>
										<li>Report 8</li>
										<li>Report 9</li>
										<li>Report 10</li>
										</ul>
									</section>
								</div>
								<div class="6u 12u(narrower)">

									<section class="special_table">
										<header>
											<h3><a href="view_vendor_contracts.php?j=<?php echo $masked_jobnumber; ?>">Vendor Contracts</a></h3>
										</header>
										<ul>
											<?php echo $vendor_contracts_table; ?>
										</ul>
									</section>

								</div>
							</div>							
	
							
							
							
							

						</section>

				</article>

			<!-- CTA -->
				<section id="cta">
				</section>

			<!-- Footer -->
				<footer id="footer">
					<ul class="copyright">
						<li>&copy; Untitled</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
					</ul>
				</footer>

		</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.scrollgress.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>