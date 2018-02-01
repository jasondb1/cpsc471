<?php
ini_set('display_errors',0); 
error_reporting(0);
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
	$groupsArray = array("admin","employee","payroll","supervisor");
	//pageProtect($usersArray,$groupsArray);

/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/	
	//get and set initial variables
		$database = new Database($dbhost, $dbname, $dbusername, $dbpass);

		$search = $_GET['search']; 

/*////////////////////////////////////////////////////////////////////////////////
Set View Options
///////////////////////////////////////////////////////////////////////////////*/		
		if (isset($_GET['update'])) {
			if (isset($_GET['showinprogress'])) {$options[0]=1;} else {$options[0]=0;}
			if (isset($_GET['showpending'])) {$options[1]=1;} else {$options[1]=0;}
			if (isset($_GET['showonhold'])) {$options[2]=1;} else {$options[2]=0;}
			if (isset($_GET['showcomplete'])) {$options[3]=1;} else {$options[3]=0;}
			if (isset($_GET['showrecurring'])) {$options[4]=1;} else {$options[4]=0;}
			if (isset($_GET['showinvoiced'])) {$options[5]=1;} else {$options[5]=0;}
			$page_rows = $_GET['page_rows'];
			$j=0;
			foreach ($headings as $x){
				$field="col_" . $j;
				if ($_GET[$field]==1) {$cols[$j]=1;} else {$cols[$j]=0;}
				++$j;
			}//end foreach

			$value = implode (":",$options);
			setcookie ('view_options',$value,mktime (0, 0, 0, 12, 31, 2015));
			$value = implode (":",$cols);
			setcookie ('view_cols',$value,mktime (0, 0, 0, 12, 31, 2015));
			setcookie ('page_rows',$page_rows,mktime (0, 0, 0, 12, 31, 2015));
			header("Location: " . $_SERVER['PHP_SELF'] . "?search=$search&page_rows=$page_rows"); 
		}
		
		$view_options = explode (":",$_COOKIE['view_options']);
		$view_cols = explode (":",$_COOKIE['view_cols']);
		if (isEnabled(array("administrator"),array("admin","supervisor"))){
			$j=0;
			if ($view_cols[0]!=""){
				foreach ($headings as $key=>$value){
						if ($view_cols[$j]==1) {$columns[$key] = $value;}
						++$j;
				}
			}
			else{
			$view_options=array(1,0,0,1,1,0);
			$view_cols=array(1,1,1,1,1,1,1,0,0,0,1,0,1,1,0,0,0,0,0,0,1);//default if no hours are
					foreach ($headings as $key=>$value){
						if ($view_cols[$j]==1) {$columns[$key] = $value;}
						++$j;
					}
			}	
		}
		else{
		//Sets the columns visible in the table format (column name=>display name) special ("edit,"amount","price","del","delete","filename","hours")
			//$columns=array("edit"=>"","jobnumber"=>"Job No","description"=>"Description","location"=>"Location","customer"=>"Customer", "supervisor"=>"Supervisor", "status"=>"Status", "notes"=>"Notes");
		}
	
		// if (isEnabled(array("administrator"),array("admin","payroll"))){
			// $columns=array("edit"=>"","jobnumber"=>"Job Number","employee"=>"Employee","date"=>"Date","time_in"=>"Time In","time_out"=>"Time Out", "hours"=>"Hours", "division"=>"Division", "sub_division"=>"Sub-Division", "comment"=>"Comment","del"=>"");
		// }
		
		$order_by = $_GET['sort_by'];
		$direction = $_GET['direction'];
		$page_rows = $_COOKIE['page_rows'];

		if ($order_by != ""){
			 $order = " ORDER BY $order_by $direction";
		}
		else{
				$order =" ORDER BY jobnumber DESC";
		}
		

		if ($search != ""){
			 $where = " jobnumber LIKE '%$search%'
						OR notes LIKE '%$search%'
						OR description LIKE '%$search%'
						OR location LIKE '%$search%'
						OR supervisor LIKE '%$search%'
						OR customer LIKE '%$search%'
						OR bill_to LIKE '%$search%'
						OR invoice_number LIKE '%$search%'
			 ";
		}
		else{
				//$where =" WHERE (status <> 'Completed' OR status = 'Recurring') AND status <> 'Permanent'";
				if( !isset( $_COOKIE['view_options'] ) ){
					$where =" ((status = 'Completed' AND invoice_number = '')  OR status = 'Recurring') AND status <> 'Permanent' OR status='In Progress'";
				}
				else{
					$n=0;
					//$where = " WHERE";
					if ($view_options[0]==1){ if ($n==1){ $where .= " OR ";}$where .= " status = 'In Progress'"; $n=1;}
					//if ($view_options[1]==1){ if ($n==1){ $where .= " OR ";}$where .= " status = 'Recurring'";$n=1'}
					//if ($view_options[2]==1){ if ($n==1){ $where .= " OR ";}$where .= " status = 'Recurring'";$n=1;}
					if ($view_options[3]==1 ){ if ($n==1){ $where .= " OR ";}$where .= " (status = 'Completed' AND invoice_number = '')"; $n=1;}
					if ($view_options[4]==1){ if ($n==1){ $where .= " OR ";}$where .= " status = 'Recurring'";$n=1;}
					if ($view_options[5]==1){ if ($n==1){ $where .= " OR ";}$where .= " invoice_number <> ''";$n=1;}
					if ($n==0){
						$where =" ((status = 'Completed' AND invoice_number = '')  OR status = 'Recurring') AND status <> 'Permanent' OR status='In Progress'";
					}
				}
		}

/*/////////////////////////////////////////////////
Delete Record
/////////////////////////////////////////////////*/
			if ($delete_record != ""){
				echo $database->deleteRecord($dbtable, "uid='$delete_record'");
				die();	
			}//end if delete

///////////////////////////////////////////////////////////////

	//$employee_list = getEmployeeNames();//get employee names
	
	//get data
	//TODO: change hard coded order below
			if ($order_by != ""){
			 $order = " ORDER BY $order_by $direction";
		}
		else{
				$order =" ORDER BY jobnumber DESC";
		}
/*/////////////////////////////////////////////////
//Create Table HTML
/////////////////////////////////////////////////*/
	
	//$db_table="jobdata";
	//$page_title="View Jobs";	
	//Edit or upload page
	//TODO: edit page into table to be associated with edit column deal with the order
	$edit_page="employee_job_entry.php";	
	$table = new Table();
	$table->title="View Jobs";
	$table->arrayColDisplayName=array("Job No","Description","Location","Customer", "Supervisor", "Status", "Notes");
	$table->arrayColName=array("jobnumber","description","location","customer", "supervisor", "status", "notes");
	$table->arrayColType=array("text","text","text","text", "text", "text", "text");
	$table->dataTable=$db_table_jobfile;
	$table->enableEdit=true;
	$table->orderByCol="jobnumber";
	$table->orderDirection = "DESC";
	$table->filter = $where;
	$table->setdb($database);
	$tableHTML = $table->toHTML();

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
				<!-- Highlight -->
				<section class="is-page-content">
					<div class="row flush" style="padding:0em; padding-top:2em;">
						<div class="12u">
							<header>
								<h3><?php echo $page_title; ?></h3>
							</header>
					
							<div id="menu">
								<ul>
								<li><a href="employee_job_entry.php" ><span class="button-menu"><i class="fa fa-plus fa-fw"></i>&nbsp; Add Job</span></a></li>
								</ul>
							</div>
							<hr>
						</div>
					</div>
					
					<div class="row flush" style="padding:0em;">
						<div class="9u" >
							<input class="submit" id="Button1" type="button" value="Show/Hide Opts" onclick="toggle_visibility('opts');" />
							<div id="opts" style="display:none; font-size: 70%;">
							<form style="background-color: rgb(244, 244, 244);" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="viewoptions">
							<input class="submit" type="submit" name="update" value="Update View"/>

								<?php
									echo '<input type="checkbox" name="showinprogress" '; 
									if($view_options[0]==1) {echo " checked=checked"; } 
									echo '><span style="color:#000000"> In Progress</span>  &nbsp;';
									echo '<input type="checkbox" name="showrecurring"'; 
									if ($view_options[4]==1) {echo " checked=checked"; } 
									echo '><span style="color:#800080">Recurring</span>  &nbsp;';
									echo '<input type="checkbox" name="showcomplete"'; 
									if($view_options[3]==1) {echo " checked=checked"; } 
									echo '><span style="color:#ff0000">Completed</span>  &nbsp;';
									//echo '<input type="checkbox" name="showpending"'; if($view_options[1]==1) {echo " checked=checked"; } echo '><span style="color:#707070">Pending</span>  &nbsp;';
									//echo '<input type="checkbox" name="showonhold"'; if($view_options[2]==1) {echo " checked=checked"; } echo '><span style="color:#707070">On Hold</span>  &nbsp;';
									if (isEnabled(array("administrator"),array("admin","supervisor"))){echo '<input type="checkbox" name="showinvoiced"'; 
									if($view_options[5]==1) {echo " checked=checked"; } 
									echo '><span style="color:#0000ff">Invoiced</span> &nbsp;';}
									echo 'Items per Page';
									//add selected for when page numbers are 
									echo '<select name="page_rows">
									<option>25</option>
									<option selected>50</option>
									<option>100</option>
									<option>500</option>
									</select>';
									echo '<br><hr>';
									
									//print out view options
									$j=0;
									foreach ($headings as $x){
										if (isEnabled(array("administrator"),array("admin","supervisor"))){
											echo '<input type="checkbox" value="1" name="col_' . $j . '"'; 
											if($view_cols[$j]==1) {echo "checked=checked"; } ; 
												echo '>' . $x . ' ';
										}//end if view cols
										 else{
											 echo '<input type="hidden" name="col_' . $j. '" value="'.$view_cols[$j].'">';
										 }
										$j++;
									}//end foreach
								?>
							</form>
						</div>
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
					<hr>
					<div class="row flush" style="padding:0em;">
						<div class="12u">	
							<b>Job Files</b>
							<?php echo $tableHTML;?>
						</div>
					</div>							
											
				</section>
			</div>
						
		</div>
					
		<div class="row">
			<div class="12u">
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
