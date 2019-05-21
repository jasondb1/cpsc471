<?php
//ini_set('display_errors',0); 
//error_reporting(0);
header("Cache-Control: private, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // A date in the past

/*////////////////////////////////////////////////////////////////////////////////
Includes
///////////////////////////////////////////////////////////////////////////////*/
	require('_config.php');
	require('Database.php');
	require('Table.php');
	require_once("phpauthent/phpauthent_core.php");
	require_once("phpauthent/phpauthent_config.php");
	require_once("phpauthent/phpauthentadmin/locale/".$phpauth_language);
	require ('_functions_common.php');
	
/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/
	$usersArray = array("administrator");
	$groupsArray = array("admin","employee","payroll","supervisor");//***
	pageProtect($usersArray,$groupsArray);

/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/	
	//get and set initial variables
		$database = new Database($dbhost, $dbname, $dbusername, $dbpass);
		
		$page_title="Timelog";									//***
		
		$dbtable		= $db_table_timelog;					//*** In _config.php
		$primaryKey		= $database->getPrimaryKey($dbtable);

/*////////////////////////////////////////////////////////////////////////////////
Set View Options/ filter results of query
///////////////////////////////////////////////////////////////////////////////*/		
		$edit_allowed_days = 30; //days allowed by employees to edit time
		
		if (isset($_GET['sort_by'])) {
			$orderBy = $_GET['sort_by'];
			$direction = $_GET['direction'];
			$page_rows = $_COOKIE['page_rows'];
		}
		else {
			$orderBy = "";
			$direction = "";
			$page_rows = "";
		}
		
		if (isset($_GET['search'])){
			$search = $_GET['search'];
		}
		else{
			$search = "";
		}
		
		$user 			= getUsername();
		$user_id		= getUserId();
		$user_email 	= getEmail(getUserId(),"phpauthent_users");
		
		$employee = $_GET['employee'];
		if ($employee == ""){
			$employee = $user_id;
		}
	
	//set initial variables
		$jobnumber = "All";
		$date = strtotime (date ("Y-m-d"));
		$date_end= cutoff_date($date);
		$display_cutoff = cutoff_date(strtotime ($date_end));
		$previous_cutoff = date ("Y-m-d" , strtotime('-14 days',strtotime($display_cutoff)));

	//if date is passed with url
		if (isset($_GET['date_current'])){
			$date_current 	= $_GET['date_current'];
			$date_end 		= $_GET['date_end'];
			$employee 		= $_GET['employee'];	
		}
	
	//if date is passed with url
		if (isset($_POST['previous'])){
			$date_current = $_POST['date_current'];
			$jobnumber = $_POST['jobnumber'];
			$employee = $_POST['employee'];	
			$display_cutoff = cutoff_date(strtotime ($date_current));
			$date_end = date ("Y-m-d" , strtotime('-14 days',strtotime($display_cutoff)));
		}
		
		if (isset($_POST['next'])){
			$date_current = $_POST['date_current'];
			$jobnumber = $_POST['jobnumber'];
			$employee = $_POST['employee'];	
			$display_cutoff = cutoff_date(strtotime ($date_current));
			$date_end = date ("Y-m-d" , strtotime('+14 days',strtotime($display_cutoff)));
		}

	//calculate payperiods and cutoffs
		$date_start = date ("Y-m-d" , strtotime('-13 days',strtotime($date_end)));
		$cutoff_code = "";
		
	//view options
		if (isset($_POST['submit']) || isset($_POST['final'])) {

			$jobnumber = $_POST['jobnumber'];
			$date_start = $_POST['date1'];
			$date_end = $_POST['date2'];
			$employee = $_POST['employee'];	
		}
		

/*/////////////////////////////////////////////////
Delete Record
/////////////////////////////////////////////////*/
	if ($_GET['delete_record'] != ""){
		echo $database->deleteRecord($dbtable, "$primaryKey='". $_GET['delete_record'] ."'");
		die();	
	}//end if delete

/*/////////////////////////////////////////////////
//Create Table HTML
/////////////////////////////////////////////////*/
	
	//*** set all of the column information
	$columns = array();
	if (isEnabled(array("administrator"),array("admin","payroll"))){
			array_push($columns, array('columnName'=>'username', 	'displayName'=>'Employee', 	'type'=>'text'));
		}
	
	array_push($columns, 
		array('columnName'=>'dateIn', 				'displayName'=>'Date', 			'type'=>'text'),
		array('columnName'=>'jobnumber',			'displayName'=>'Jobnumber',		'type'=>'jobnumber'),
		array('columnName'=>'hours',				'displayName'=>'Hours',			'type'=>'hours'),
		array('columnName'=>'time_in',				'displayName'=>'Time In',		'type'=>'time_in'),
		array('columnName'=>'time_out',				'displayName'=>'Time Out',		'type'=>'time_out'),
		array('columnName'=>'comment',				'displayName'=>'Comment',		'type'=>'text')
		
	);

	//Search Criteria
	if ($search != ""){
		$i = 0;
		foreach ($columns as $row){
			if ($i == 0){ 
				$where = " " . $row['columnName'] . " LIKE '%$search%'";
			}
			else {
				$where .= " OR " . $row['columnName'] . " LIKE '%$search%'";
			}
			$i++;
		}	
	}
	else{
		//$where =" true"; //default criteria
		$where = "";
		$where = " (`dateIn` BETWEEN '$date_start' AND '$date_end')";
		if ($employee != "All"){$where .= " AND phpauthent_users.Id = '$employee'";}
		if ($jobnumber != "All" AND $jobnumber!=""){$where .= " AND Timelog.jobnumber = '$jobnumber'";}
		$order = " ORDER BY `dateIn`,`time_in`";
	}
		
	//custom sql code for table columns for table specified above
	if ($orderBy == ''){ $orderBy = "dateIn";}
	
	$sql = "SELECT Timelog.*, phpauthent_users.username, phpauthent_users.realname FROM Timelog JOIN phpauthent_users ON Timelog.user_id = phpauthent_users.id WHERE " . $where .	" ORDER BY " . $orderBy . " " .$direction;
	$retval = $database->query($sql);
		
	//new table object
	$table = new Table();
	$table->dataTable=$dbtable;
	$table->columns=$columns;
	
	$table->enableEdit=true;								//*** change these to admin only after certain date
	$table->enableDelete=true;								//*** change these to admin only after certain date
	$table->editPage="employee_timelog_entry.php";			//***
	
	$table->orderByCol= $orderBy;
	$table->orderDirection = $direction;
	$table->filter = $where;
	$table->setdb($database);
	
	$tableHTML = $table->toHTML($sql);		//gets the actual html code that is used below

	// may use this at the end of table
	//	$sql = "SELECT SUM(hours) AS totalhours FROM $db_timelog".$where;

?>


<!DOCTYPE HTML>
<!--
	TXT 2.5 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title><?php echo $page_title?></title>
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
		
		<link rel="stylesheet" href="css/jquery-ui.min.css">
		<link rel="stylesheet" href="css/jquery-ui.theme.min.css">
		<link rel="stylesheet" href="css/jquery-ui.structure.min.css">
		<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">

		<script src="js/jquery-ui.min.js"></script>
		<script src="js/jquery-ui-timepicker-addon.js"></script>
		<script>
			$(function() {
				$( "#datepicker1" ).datepicker(
					{
					dateFormat: "yy-mm-dd",
					showButtonPanel: true,
					changeMonth: true,
					changeYear: true,
					showWeek: true,
					firstDay: 1
					}
				);
				$( "#datepicker2" ).datepicker(
					{
					dateFormat: "yy-mm-dd",
					showButtonPanel: true,
					changeMonth: true,
					changeYear: true,
					showWeek: true,
					firstDay: 1
					}
				);
			});
		</script>
		<script type="text/javascript">
		<!--
			function toggle_visibility(id) {
			   var e = document.getElementById(id);
			   if(e.style.display == 'block')
				  e.style.display = 'none';
			   else
				  e.style.display = 'block';
			}
		//-->
		</script>
		<style type="text/css">
		  #datepicker1, #datepicker2{
			background-image:url("images/calendar.png");
			background-position:right center;
			background-repeat:no-repeat; }
		</style>
		
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
				<section class="is-page-content">
					<div class="row flush" style="padding:0em; padding-top:2em;">
						<div class="12u">
							<header>
								<h3><?php echo $page_title; ?></h3>
							</header>
					
							<div id="menu">
								<ul>
								<li><a href="employee_timelog_entry.php" ><span class="button-menu"><i class="fa fa-plus fa-fw"></i>&nbsp; Add Timelog Entry</span></a></li>
								</ul>
							</div>
							<hr>
						</div>
					</div>
					
					<div class="row flush" style="padding:0em;">
						<div class="9u" >
							&nbsp;
						</div>
						<div class="3u">
							<form style="background-color: rgb(255, 255, 255);" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="viewoptions">
								<?php  
									echo '<input class="text" type="text" name="search" placeholder="Search" value="'.$search. '">';
									echo '<input type="submit" name="submit" value="Search"/>';
								?>
							</form>
						</div>							
					</div>
					
					<div class="row flush" style="padding:0em;">					
						<div class="12u">
							<hr>
							<form style="" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="viewoptions">
								<input class="submit" type="submit" name="previous" value="Previous Cutoff">
								<input class="submit" type="submit" name="next" value="Next Cutoff">
								<br>
								<input name="employee" type="hidden" value=<?php echo $employee;?>>
								<input name="date_current" type="hidden" value=<?php echo $date_end;?>>
								<input type="hidden" name="jobnumber" value="<? echo $jobnumber;?>">
							</form>
							<hr>
						</div>
					</div>
					
					
					<div class="row flush" style="padding:0em;">
						<div class="6u" style="border-right:1px;">
							<form style="" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="viewoptions">
								<label>Start Date:</label><input id="datepicker1" class="text" name="date1" type="textbox" readonly="readonly" value="<?php echo $date_start; ?>">
								<label>End Date:</label><input class="text" id="datepicker2" name="date2" type="textbox" readonly="readonly" value="<?php echo $date_end; ?>">
								
								<?php 
								if (isEnabled(array("administrator"),array("admin","payroll"))){
									echo '<hr><label>Job Number:</label>';
									// echo '<select name="jobnumber">';
									// echo '<option>All</option>';
									// foreach ($job_number_list as $x){
									// echo "<option";
									// if ($x == $jobnumber) {echo " selected";}
									// echo ">$x</option>";}
									// echo '</select>';
									echo '<input class="text" type="text" name="jobnumber" value="'. $jobnumber . '">';
									echo '<label>Employee:</label>';
									echo '<select name="employee">';
									echo '<option>All</option>';
								
		//TODO: update employee list 
									$employee_list = getEmployeeNames();
									foreach ($employee_list as $x=>$y){
										echo "<option";
										if ($x == $employee) {echo " selected";}
										echo ">$y</option>";
									}
									echo '</select>';
									//echo '<input class="submit" type="submit" name="submit" value="Update View";/>';
								}
								else{
									echo '<input type="hidden" name="employee" value="'. $employee . '">';
									echo '<input type="hidden" name="jobnumber" value="All">';
								}
								?>
								<input class="submit" type="submit" name="submit" value="Update View";/>
							</form>
						</div>	
						<div class="-2u 4u">
							<br><!--<hr> -->
							<form style="" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="viewoptions">
								<input name="employee" type="hidden" value=<?php echo $employee;?>>
								<input type="hidden" name="jobnumber" value="All">
								<input name="date_current" type="hidden" value=<?php echo $date_end;?>>
								<input id="datepicker1" name="date1" type="hidden" value=<?php echo $date_start;?>>
								<input id ="datepicker2" name="date2" type="hidden" value=<?php echo $date_end;?>>
								<!-- Submit Timesheet
								<input name="final" type="checkbox" value="1">Confirm timesheet submission for dates shown<br>
								<input class="submit" name="submit_time" value="Submit Timelog" type="submit">
								-->
							</form>
						</div>
											
					</div>
								<hr>
					
					<div class="row flush" style="padding:0em;">
						<div class="12u">	
							<?php echo $tableHTML;?>
						</div>
					</div>							
											
				</section>
			</div>		
		</div>
		<!-- /Main -->

		<!-- Footer -->
			<footer id="footer" class="container">
				<!-- Copyright -->
				<div id="copyright">
					&copy; <?php echo $company_name; ?> | Template: <a href="http://html5up.net/">HTML5 UP</a>
				</div>
				<!-- /Copyright -->
			</footer>
		<!-- /Footer -->

	</body>
</html>
