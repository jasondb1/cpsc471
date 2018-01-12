<?php
//ini_set('display_errors',0); 
//error_reporting(0);
header("Cache-Control: private, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // A date in the past

/*////////////////////////////////////////////////////////////////////////////////
Functions
///////////////////////////////////////////////////////////////////////////////*/

/*////////////////////////////////////////////////////////////////////////////////
Includes
///////////////////////////////////////////////////////////////////////////////*/
	require('_config.php');
	require_once("phpauthent/phpauthent_core.php");
	require_once("phpauthent/phpauthent_config.php");
	require_once("phpauthent/phpauthentadmin/locale/".$phpauth_language);
	require_once("_functions_common");
	
/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/
	$usersArray = array("administrator");
	$groupsArray = array("admin","payroll","supervisor");
	pageProtect($usersArray,$groupsArray);

/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/	
	//get and set initial variables
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		
		$user 			= getUsername();
		$user_email 	= getEmail(getUserId(),"phpauthent_users");
		$jobnumber		= $_GET['j'];
		
		//Sets the columns visible in the table format (column name=>display name) special ("edit,"amount","price","del","delete","filename","hours")
			$columns=array("date"=>"Date","employee"=>"Employee","time_in"=>"In","time_out"=>"Out", "hours"=>"Hours", "division"=>"Div", "sub_division"=>"Sub-Div", "comment"=>"Comment","approved"=>"Approved");
		
		//***Check if Supervisor of the job...
		
		// if (isEnabled(array("administrator"),array("admin","payroll"))){
			// $columns=array("edit"=>"","jobnumber"=>"Job No","employee"=>"Employee","date"=>"Date","time_in"=>"In","time_out"=>"Out", "hours"=>"Hours", "division"=>"Div", "sub_division"=>"Sub-Div", "comment"=>"Comment","del"=>"");
		// }
		
		$db_table="timelog";
		$page_title="Timelog";	

	
		
	
/*/////////////////////////////////////////////////
Delete Record
/////////////////////////////////////////////////*/
			if ($delete_record!=""){
	
				$mysql_link("DELETE FROM $db_table WHERE uid='$delete_record'");
				 //write log
					$details="jn:$jobnumber,$date,$start_time,$end_time,$delete_record";
					write_log_file ($user,'Time Delete',$employee,$details);
				echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
				echo "<br><br><b><big>Successfully Deleted</b></big><br><br>";
				echo 	'<input type="Button" value="Back" onclick="location.href=\''.$_SERVER['PHP_SELF']  .'?j='.$masked_jobnumber  .'&date_current='. $date_current .'&date_end='. $date_end .'&employee='.$employee   .'\'">';
				die();		
			}//end if delete

///////////////////////////////////////////////////////////////

	$employee_list = getEmployeeNames();//get employee names
	
	//get data
		$where = "";
		$where = " WHERE jobnumber = '$jobnumber'";
		$order = " ORDER BY `date`,`time_in`";
		$sql = "SELECT * FROM $db_timelog" . $where . $order;
		//echo $sql;
		$retval = $mysql_link->query($sql);
		
		$sum = $mysql_link->query("SELECT SUM(hours) AS totalhours FROM $db_timelog".$where);
		while($row=$sum->fetch_assoc())
		{
			$total_hours = $row['totalhours'];
		}

///////////////////////////////////////Table 2 - Calculate Summary by Day

	//date start to date end
	$table2 = '<div style="color:#000; text-align:left;"><br><br><b>Daily Total</b><br>';
	$t1=strtotime($date_start);
	$t2=strtotime($date_end);	
	$oneday = (24*60*60);
	$x=$t1;
	while ($x<=$t2){
		$d1= date ("Y-m-d",$x);
		$where1 = " WHERE (`date` = '$d1')";
		//if ($employee != "All"){$where1 .= " AND employee = '$employee'";}
		$where1 .= " AND jobnumber = '$jobnumber'";
		$sql = "SELECT * FROM $db_timelog" . $where1;
		$sum = $mysql_link->query("SELECT SUM(hours) AS totalhours FROM $db_timelog".$where1.$order);	
			while($row=$sum->fetch_assoc())
				{
					$hrs = $row['totalhours'];
				}
		if ($hrs>0){$table2 .= date("D-M-d",$x) . ": <b>" . round($hrs, 2) . "</b><br>";}
			$x+=$oneday;
	}
	$table2 .="</div>";

///////////////////////////////////////Table 1 - Main Calculate Summary by Day	
//table code
		$table = '<table id="dbtable" style="width: 100%;"><tr>';
			//Show Headings
				foreach ($columns as $x=>$y){
					$heading=$y;
					// if ($direction =="ASC") {
						// $table .= "<th><b><a href=". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=DESC><b>$heading</b></a></b></th>";
					// }
					// else {
						// $table .= "<th><b><a href='". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=ASC'><b>$heading</b></a></b></th>";
					// }
					$table .= "<th><b>$heading</b></th>";
				  }
				$table .= '</tr>';
			//Output the data		
				$j=1;
				while($row = $retval->fetch_assoc())
				{
					// Color odd lines
					if ($j % 2 == 0){ 
						$table .= '<tr style="background-color: #f0f0f0;">'; 
					} 
					else { 
						$table .= '<tr style="background-color: #e0e0e0;">';
					}
					foreach ($columns as $i=>$value){
						$color = "000000";//basic color
							// foreach ($status_color as $status_key=>$color_value){
								// if ($row['status']==$status_key){
								// $color=$color_value;
								// }
							// }
						$table .=  '<td style="color:#' . $color . ';">';  
				//check if special cell and format set formatting conditions				
						if ($i == "edit") { 
							if (strtotime($row['date'])>mktime(0,0,0,date("m"),date("d")-$edit_allowed_days,date("y"))){
								$id_number = $row['uid'];
								
								$table .= '<a href="'.$edit_page .'?id='. $id_number . '">
								<span style="color:#092;">
								<i class="fa fa-edit fa-lg fa-fw"></i>
								</span></a></td>';
							}
						}
						elseif ($i == "filename"){
							$filename=$row[$i];
							$table .= '<a href="'.	$path . $filename.'"><span style="color:#00f;"><i class="fa fa-file-o fa-lg fa-fw"></i></span></a>';
						}
						elseif ($i=="amount" || $i=="price"){ 
							$table .= money_format("%n",$row[$i]);
						}
						elseif ($i=="date"){ 
							$table .= date("D Y-m-d", strtotime($row[$i]));
						}
						elseif ($i=="hours"){ 
							$table .= sprintf("%01.2f", $row[$i]).'</td>';
						}
						elseif ($i == "time_in" or $i == "time_out") {
						$table .= date ("H:i",strtotime($row[$i]));  
						}
						elseif ($i == "approved" || $i=="approve" ) {
						$id=$row['uid'];
						$table .= '<input type="checkbox" name="approved[]" value="'. $id     .'" checked=';
						if ($row[$i]==1 or $row[$i]==NULL){$table .="checked ";}
						$table .= 'checked />';  
						}
						elseif ($i=="delete" || $i=="del"){ 
						if (strtotime($row['date'])>mktime(0,0,0,date("m"),date("d")-$edit_allowed_days,date("y"))){
							$table .=  '<a href="'.$_SERVER['PHP_SELF'].'?delete_record=' . $row['uid'] . '&j='. $masked_jobnumber . '&date_current='. $date_current.'&date_end='.$date_end.'&employee='.$employee.'" onclick="return confirm(\'Confirm Delete?\')" ><span style="color:#f00;"><i class="fa fa-minus-circle fa-lg fa-fw"></i></span></a>';
							//$table .=  '<a href="" onClick="checkDelete(\''.$row['uid'].'\')" ><span style="color:#f00;"><i class="fa fa-minus-circle fa-lg fa-fw"></i></span></a></td>';
							
							}
						}
				//Regular Cell	
						else{ 
							$table .= $row[$i] . "</td>"; 
						}	
					}//end foreach for column

				// add extra columns (if required)	
					$table .= '</tr>';
					$j++;
				}//end while
			
		$table .='</table>';
	$table .= '<div style="color:#000; text-align:left;"><b>Total Hours:</b>'. round($total_hours,2) . "</div>";	

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
		
		<link rel="stylesheet" href="css/jquery-ui.min.css">
		<link rel="stylesheet" href="css/jquery-ui.theme.min.css">
		<link rel="stylesheet" href="css/jquery-ui.structure.min.css">
		<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">

		<script src="js/jquery-ui.min.js"></script>
		<script src="js/jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript">
    //<![CDATA[

    var tabLinks = new Array();
    var contentDivs = new Array();

    function init() {

      // Grab the tab links and content divs from the page
      var tabListItems = document.getElementById('tabs').childNodes;
      for ( var i = 0; i < tabListItems.length; i++ ) {
        if ( tabListItems[i].nodeName == "LI" ) {
          var tabLink = getFirstChildWithTagName( tabListItems[i], 'A' );
          var id = getHash( tabLink.getAttribute('href') );
          tabLinks[id] = tabLink;
          contentDivs[id] = document.getElementById( id );
        }
      }

      // Assign onclick events to the tab links, and
      // highlight the first tab
      var i = 0;

      for ( var id in tabLinks ) {
        tabLinks[id].onclick = showTab;
        tabLinks[id].onfocus = function() { this.blur() };
        if ( i == 0 ) tabLinks[id].className = 'selected';
        i++;
      }

      // Hide all content divs except the first
      var i = 0;

      for ( var id in contentDivs ) {
        if ( i != 0 ) contentDivs[id].className = 'tabContent hide';
        i++;
      }
    }

    function showTab() {
      var selectedId = getHash( this.getAttribute('href') );

      // Highlight the selected tab, and dim all others.
      // Also show the selected content div, and hide all others.
      for ( var id in contentDivs ) {
        if ( id == selectedId ) {
          tabLinks[id].className = 'selected';
          contentDivs[id].className = 'tabContent';
        } else {
          tabLinks[id].className = '';
          contentDivs[id].className = 'tabContent hide';
        }
      }

      // Stop the browser following the link
      return false;
    }

    function getFirstChildWithTagName( element, tagName ) {
      for ( var i = 0; i < element.childNodes.length; i++ ) {
        if ( element.childNodes[i].nodeName == tagName ) return element.childNodes[i];
      }
    }

    function getHash( url ) {
      var hashPos = url.lastIndexOf ( '#' );
      return url.substring( hashPos + 1 );
    }

    //]]>
    </script>
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
	<body class="homepage" onload="init()">

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
										<h3>Timelog View - Current Period:<?php echo $date_start . " to " . $date_end ?></h3>
									</header>
						
											<div id="menu">
												<ul>
												<li><a href="_employee_menu.php" ><span class="button-menu"><i class="fa fa-arrow-circle-o-left fa-fw"></i>&nbsp; Employee Menu</span></a></li>
												</ul>
											</div>
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
											</div>	
											<div class="-2u 4u">
												<br>
											
													
											
											</div>
											
					</div>
								
								<div class="row flush" style="padding:0em;">
											<div class="12u">
											
											
											<form style="" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="timeapprove">											
												<h3>Time</h3>
												<?php echo $table;?>
												
												<input class="submit" name="submit_time" value="Approve Hours" type="submit">
												<?php echo $table2;?>
												</form>
											
											
											
											
											
											</div>
											
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