<?php
require_once("../phpauthent/phpauthent_core.php");
require_once("../phpauthent/phpauthent_config.php");
include ("cfg_dashboard.php");
	
	/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/
	$usersArray = array("administrator");
	$groupsArray = array("admin","employee","payroll","supervisor");
	pageProtect($usersArray,$groupsArray);

	$masked_jobnumber = $_GET['j'];
	$jobnumber = xor_this($masked_jobnumber);
	$delete_record=$_GET['delete_record'];
	$path=$invoice_path;
	$db_table="vendor_invoices";
	$sort_column = $_GET['sort_by'];
	
	//$jobnumber = "11660"; //for testing only
	if($sort_column==""){
		$sort_column = 'division';
	}
	
	//Edit or upload page
	$edit_page="upload_vendor_invoice.php";	
	
	//Sets the columns visible in the table format (column name=>display name)
	$columns=array("invoice_date"=>"Date","vendor"=>"Vendor","invoice_number"=>"Inv. #","division"=>"Div","amount"=>"Amount","edit"=>"","filename"=>"");

	//color line text if status is ("status value"=>"d0d0d0")
	//$status_color=array("Completed"=>"00ff00,"Outstanding"=>"ff0000","Other"=>"d0d0d0");
	
//////////////////////////////////////Search and Order Criteria

			//search function
				 //$search = $_GET['search']; 
				// if ($search!=""){
					 // $where = " WHERE description LIKE '%$search%'
								// OR notes LIKE '%$search%'
								// OR vendor LIKE '%$search%'
								// OR status LIKE '%$search%'
								// OR division LIKE '%$search%'
								// AND jobnumber = '$jobnumber'
					 // ";
				// }
				// else{
						$where =" WHERE jobnumber = '$jobnumber'";
				// }

			//Order if column clicked
				$order_by = $_GET['sort_by'];
				$direction = $_GET['direction'];

				if ($order_by != ""){
					 $order = " ORDER BY $order_by $direction";
				}
				else{
						$order =" ORDER BY date_entered DESC";
				}
		
	//connect to database
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}	
				
		 
		//   First get total number of rows in data table. 
		//  If you have a WHERE clause in your query, make sure you mirror it here.
		
			// $sql = "SELECT * FROM $db_table" .$where;
				// $result = mysql_query($sql) or die(mysql_error());
				 // $rows = mysql_num_rows($result);
				 
			 // if ($rows == 0) {
				 // echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
				// echo "<br><br><b><big>No Data</b></big><br><br>";
				// //$href_string= $edit_page . "?j=" . $masked_jobnumber ;
				// //echo 	'<input type="Button" value="Enter Document" onclick="location.href=\''. $href_string .'\'">';
				// echo 	'<input type="Button" value="Back" onClick="history.go(-1)" />';
				// die();
			 // }	
			 
/////////////////// Get data from table

					// $where = " WHERE jobnumber='$jobnumber'";
					// //$sql = "SELECT * FROM $db_table". $where . $order . $max;
					// $sql = "SELECT `$sort_column` FROM $db_table". $where . $order;
					// $result = mysql_query($sql) or die('Could not retrieve data: ' . mysql_error());
					// while($row = mysql_fetch_array($result))
						// {
						// $division_list[] = $row[$sort_column];
						// }
					// print_r($division_list);
					//sql for division
		//get divisions via sql - for put in temporarily
				//for ($n = 0; $n <= 100; $n++){
				$grand_total=0;
				//print_r($divisions);
				$divisions = $divisions_list;
				foreach ($divisions as $n=>$value){
				//echo $n;
					$table .= "<b>$sort_column:".$n."</b>";
					
				//sql	
					$where = " WHERE jobnumber='$jobnumber' AND `$sort_column`='$n'";
					//$sql = "SELECT * FROM $db_table". $where . $order . $max;
					$sql = "SELECT * FROM $db_table". $where . $order;
					$result = mysql_query($sql) or die('Could not retrieve data: ' . mysql_error());
					$total_amount=0;
					
					//table code
					$table .= '<table style="width: 100%; font-size:1em;"><tr>';
						//Show Headings
							foreach ($columns as $x=>$y){
								$heading=$y;
								if ($direction =="ASC") {
									$table .= "<th style='text-align:left;'><b><a href=". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=DESC><b>$heading</b></a></b></th>";
								}
								else {
									$table .= "<th style='text-align:left;'><b><a href='". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=ASC'><b>$heading</b></a></b></th>";
								}
							  }
							$table .= '</tr>';
						//Output the data		
							$j=1;
						
						while($row = mysql_fetch_array($result))
						{
							// Your while loop here
							// Color odd lines
							if ($j % 2 == 0){ 
								//$table .= '<tr style="background-color: #f0f0f0;">'; 
								$table .= '<tr>';
							} 
							else { 
								//$table .= '<tr style="background-color: #e0e0e0;">';
								$table .= '<tr>';
							}
							
							foreach ($columns as $i=>$value){
								$color = "000000";//basic color								
								$table .=  '<td style="color:#' . $color . ';">';  
									
						//check if special cell and format set formatting conditions				
								if ($i == "edit") { 
									$id_number = $row['uid'];
									$table .= '<a href="'.$edit_page .'?id='. $id_number . '"><img src="img/i_edit.png" /></a></td>';
								}
								elseif ($i == "filename"){
									$filename=$row[$i];
									$table .= '<a href="'.	$path . $filename.'"><img src="img/doc.png" /></a>';
								}
								elseif ($i=="amount" || $i=="price"){ 
									//$table .= sprintf("$%01.2f", $row[$i]).'</td>';
									$table .= money_format("%n",$row[$i]);
									$total_amount += $row[$i];
									$grand_total += $row[$i];
								}
								elseif ($i=="hours"){ 
									$table .= sprintf("%01.2f", $row[$i]).'</td>';
								}
								elseif ($i=="delete" || $i=="del"){ 
									$table .=  '<a href="" onClick="checkDelete(\''.$row['uid'].'\')" ><img src="img/i_delete.png" /></a>';
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
						
						$table .= "<td></td><td></td><td style='color:#000000; text-align:right;'>$sort_column $n Total:</td><td style='color:#000000; text-align:left;'><b>" . money_format('%n',$total_amount) . "</b></td><td></td>";
			
					$table .='</table>';
				}//end for

				$table .= '<br><br>Grand Total:'.money_format('%n',$grand_total);

	
?>


<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pl" xml:lang="pl">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>View Site Instructions</title>
	<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="css/navi.css" media="screen" />
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script src="../js/jquery.js"></script>
	<script src="../js/anytime.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/anytime.css" />
	<script type="text/javascript">
	$(function(){
		$(".box .h_title").not(this).next("ul").hide("normal");
		$(".box .h_title").not(this).next("#home").show("normal");
		$(".box").children(".h_title").click( function() { $(this).next("ul").slideToggle(); });
	});
	</script>
	
	<style type="text/css">
		  #date1, #date2, #date3{
			background-image:url("images/calendar.png");
			background-position:right center;
			background-repeat:no-repeat; }
			label {
			float:left;
			width:130px;
			text-align:right;
			}
			select {
			float:left;
			text-align:left;
			}
	</style>
</head>

<body>
<div class="wrap">
	<div id="header">
		<div id="top">
			<div class="left">
				<!--<p>Welcome, <strong>Employee</strong> [ <a href="">logout</a> ]</p>-->
			</div>
			<div class="right">
				<div class="align-right">
					<!--<p>Last login: <strong>(Login Date)</strong></p>-->
				</div>
			</div>
		</div>

		<div id="nav">
		 Dashboard - Jobnumber: <?php echo $jobnumber;?>
		</div>

	</div>
	
	<div id="content">
		<div id="sidebar">
			<?php include ("cfg_menu.php");?>
		</div>
		
	<div id="main">
		<!-- //Main Area -->
		<div class="full_w">
				<div class="h_title">Invitation to Bid</div>
				<h2>Vendor Invoices :: Jobnumber - <?php echo $jobnumber;?></h2>
				
				<!-- Put search stuff here etc -->
					<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="viewoptions">
					<input type="hidden" name="j" value="<?php echo $masked_jobnumber; ?>" />
						<?php  
							echo '<br><input style="background-color:#FFFFCC; border-style:solid; border-width:medium;" type="text" name="search" placeholder="Search" value="'.$search. '">';
							echo '<input type="submit" name="submit" value="Search"/>';
						?>
					</form>
				
				<div class="entry">
					<div class="sep"></div>
				</div>
		<!-- Table goes below here -->
		<?php echo $table;?>
		
		
	</div>
			
			<div class="clear"></div>
	</div>

</body>
</html>
