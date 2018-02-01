<?php
require_once("../phpauthent/phpauthent_core.php");
require_once("../phpauthent/phpauthent_config.php");
	include_once ("cfg_dashboard.php");
	
	/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/
	$usersArray = array("administrator");
	$groupsArray = array("admin","employee","payroll","supervisor");
	pageProtect($usersArray,$groupsArray);

	$jobnnumber=$_GET['jobnumber'];
	$id = $_GET['id'];
	$edit_record = $_REQUEST['record_edit'];
	
	//connect to database
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
			
//////////////////////////////////////Process Submit
	if (isset($_POST['submit'])){
		
	//$_Post variables

		 $jobnumber		= $_POST['jobnumber']; 
		 		
	//enter data into new dashboard_project table	
		
			$sql = "INSERT INTO dashboard_project (
			 `jobnumber`
			)
			VALUES (
			'$jobnumber'
			)";
	
		//echo $sql;
		$retval = $mysql_link->query($sql) or die($mysql_link->error);
		
	//exit message	
		echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
		echo "<br><br><b><big>Successfully Submitted</b></big><br><br>";
		echo 	'<input type="Button" value="Back" onclick="location.href=\'dashboard.php\'">';
		die();
		
	}//end if submit
	
	

	
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Project Dashboard</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		
		<script language="javascript">
	function checkDelete(id) {
		if (confirm("Confirm Delete")) {
			location.href="<?=$_SERVER['PHP_SELF'];?>?delete_record="+ id + "&mode=remove" + "&j=<? echo $masked_jobnumber;?>";
			return true;
		} else {
			return false;
		}
	}
	</script>
	
		
	</head>
	<body class="index">
		<div id="page-wrapper">
			
		

						<!-- Header -->
			<?php include ("header.php");?>

			<!-- Banner 
				<section id="banner_small">

					<!--
						".inner" is set up as an inline-block so it automatically expands
						in both directions to fit whatever's inside it. This means it won't
						automatically wrap lines, so be sure to use line breaks where
						appropriate (<br />).
					-->


						<!-- Main -->
				<article style="padding-top:4em;" id="main">


					<!-- Three -->
						<section class="wrapper style3 container special">			
<!-- //Row 1 -->							
							<div class="row">
								<div class="12u">
									<h2><strong>Add Job</strong></h2>
										
										<ul class="buttons vertical">

											<li>
												<input type="button" name="action" class="back button fixedwidth" value="back" onclick="window.location.href='<?php echo "dashboard.php?j=" . $masked_jobnumber;?>'">
											</li>
										</ul>
										
									<section class="special_table" >
										<div class="row">
											<div class="12u">

												<!-- Put search stuff here etc -->
												<form style="background-color:#ffffff;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
												<fieldset>
												<legend>Input Information</legend>
													
														<label for="jobnumber">Jobnumber</label>
														<input type="text" id="jobnumber" name="jobnumber" value="<?php echo $jobnumber;?>" />

													<div class="entry">
														<button type="submit" name="submit" class="add">Add Job</button> <button class="cancel">Cancel</button>
													</div>

												</fieldset>
												</form>

											</div>
										</div>									
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
						<li>&copy; Assurance Construction</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
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
