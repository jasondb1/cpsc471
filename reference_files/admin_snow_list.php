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

		$table="";
		foreach ($snow_sites as $key=>$value){
			$table .= '<input type="hidden" name=site_list[] value="'.$value.'">';
			$table .= $value . ' <a href="'.$_SERVER['PHP_SELF'].'?delete='.$key.' ">Remove</a><br>';
		}
		$table .= '<input type="textbox" name=site_list[] /><br>';
		$table .= '<input type="textbox" name=site_list[] /><br>';

/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/	
//get and set initial variables
	$user 				= getUsername();
			
/*////////////////////////////////////////////////////////////////////////////////
Process Form
///////////////////////////////////////////////////////////////////////////////*/
	
	if (isset($_POST['submit']) || isset($_GET['delete'])){

			$delete = $_GET['delete'];
			if(!isset ($delete)){
				$delete = -1;
			}
			$filename = '_snowsites.php';
			$posted_list = $_POST['site_list'];
			if (isset($_GET['delete'])){
				$posted_list = $snow_sites;
			}
//echo "list:";
//print_r ($posted_list);
			$list = array_filter($posted_list);
//echo "filtered list:";
//print_r ($list);
			$count = count($list);
			$content = '<?php $snow_sites = array(';
			$j=0;
			//if (!isset ($delete)){
			foreach ($list as $key=>$value){
				if ($key != $delete){
					$content .= "\"$value\"";
					if ($j < $count -1 ){
						$content .= ",";
					}
				}
				++$j;
			}
			//}
			
			$content .= ');sort ($snow_sites);';
			$content .= '?>';
			
			// Make sure the file exists and is writable
			if (is_writable($filename)) {
				if (!$handle = fopen($filename, 'w')) {
					 echo "Cannot open file ($filename)";
					 exit;
				}

				// Write $content to our opened file.
				if (fwrite($handle, $content) === FALSE) {
					echo "Cannot write to file ($filename)";
					exit;
				}
				//echo "Success, wrote ($content) to file ($filename)";
				fclose($handle);

			} else {
				echo "The file $filename is not writable";
			}
			
			header('Location:'.$_SERVER['SCRIPT_NAME']   .' ');
	
}//end if submit

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
		
		<link rel="stylesheet" href="css/jquery-ui.min.css">
		<link rel="stylesheet" href="css/jquery-ui.theme.min.css">
		<link rel="stylesheet" href="css/jquery-ui.structure.min.css">
		<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">

		<script src="js/jquery-ui.min.js"></script>
		<script src="js/jquery-ui-timepicker-addon.js"></script>
		
		
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
										<h3>Snow Sites</h3>
									</header>
									<div id="menu">
										<ul>
										<li><a href="employee_snow_view.php" ><span class="button-menu"><i class="fa fa-arrow-circle-o-left fa-fw"></i>&nbsp; View Snow Log</span></a></li>
										</ul>
									</div>
								</div>
							</div>
							<!-- /Menu Buttons -->
							<!-- Form -->
							<div class="row flush" style="padding:0em;">					
								<div class="12u">
									<hr>
									<form class="formLayout" style="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="timelog">
										 <fieldset>
											<legend>Edit Snow List</legend>
												<div style="text-align: left;">
													<?php  
														echo $table;
													?>
													<input value="Save List" name="submit" type="submit"><br>
													
												</div>			
											</fieldset>
																					
									</form>

					
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