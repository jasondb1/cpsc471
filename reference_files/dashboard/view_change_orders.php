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

	$masked_jobnumber = $_GET['j'];
	$jobnumber = xor_this($masked_jobnumber);
	$delete_record=$_GET['delete_record'];
	$path=$po_path;
	$db_table="purchase_order";
	
	//Edit or upload page
	$edit_page="employee_po_entry.php";	
	
	//Sets the columns visible in the table format (column name=>display name)
	$columns=array("edit"=>"Edit","po_number"=>"PO Number","division"=>"Div","changeorder"=>"CO","vendor"=>"Vendor","date_entered"=>"Date Entered","price"=>"Price","status"=>"Status");

	//color line text if status is ("status value"=>"d0d0d0")
	//$status_color=array("Completed"=>"00ff00,"Outstanding"=>"ff0000","Other"=>"d0d0d0");
	
	
//////////////////////////////////////Search and Order Criteria

			//search function
				 $search = $_GET['search']; 
				if ($search!=""){
					 $where = " WHERE (description LIKE '%$search%'
								OR notes LIKE '%$search%'
								OR purchase_order_items.po_number LIKE '%$search%'
								OR status LIKE '%$search%'
								OR purchase_order_items.division LIKE '%$search%'
								OR changeorder LIKE '%$search%'
								OR vendor LIKE '%$search%')
								AND jobnumber = '$jobnumber'
					 ";
				}
				else{
						$where =" WHERE jobnumber = '$jobnumber'";
				}

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
		
			//$sql = "SELECT * FROM $db_table" .$where;
			
			$sql ="SELECT purchase_order_items.*, purchase_order.vendor, purchase_order.date_received, purchase_order.status
					FROM purchase_order_items
					LEFT JOIN purchase_order
					ON purchase_order_items.po_number=purchase_order.po_number
					". $where . $order. $max;
			
				$result = $mysql_link->query($sql) or die(mysql_error());
				 $rows = mysql_num_rows($result);
				 
			 if ($rows == 0) {
				 echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
				echo "<br><br><b><big>No records found</b></big><br><br>";
				echo 	'<input type="Button" value="Enter Document" onclick="location.href=\''. $edit_page . '?j=' . $masked_jobnumber  .'\'">';
				die();
			 }	
			 
 /////////////////////////////////////////////////Delete Record
			if ($delete_record!=""){	
				mysql_query("DELETE FROM $db_table WHERE uid='$delete_record'") or die(mysql_error());  

				echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
				echo "<br><br><b><big>Successfully Deleted</b></big><br><br>";
				echo 	'<input type="Button" value="Back" onclick="location.href=\''.$_SERVER['PHP_SELF']  .'?j='.$masked_jobnumber  .'\'">';
				die();		
			}//end if delete
		
/////////////////// Get data from table
			$sql ="SELECT purchase_order_items.*, purchase_order.vendor, purchase_order.date_received, purchase_order.status
					FROM purchase_order_items
					LEFT JOIN purchase_order
					ON purchase_order_items.po_number=purchase_order.po_number
					". $where . $order . $max;

			$result = $mysql_link->query($sql) or die('Could not retrieve data: ' . mysql_error());
			
		//table code
		$table = '<table class="default" style="width: 100%;"><thead><tr>';
			//Show Headings
				foreach ($columns as $x=>$y){
					$heading=$y;
					if ($direction =="ASC") {
						$table .= "<th><a href=". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=DESC>$heading</a></th>";
					}
					else {
						$table .= "<th><a href='". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=ASC'>$heading</a></th>";
					}
				  }
				$table .= '</tr></thead><tbody>';
			//Output the data		
				$j=1;
				while($row = $result->fetch_assoc())
				{
					// Your while loop here
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
							$subtotal += $row[$i];
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
			
		$table .='</tbody></table>';

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
		
	<script src="../js/anytime.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/anytime.css" />
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
									<h2><strong>Purchase Orders</strong> :: Jobnumber - <?php echo $jobnumber;?></h2>
										
										<ul class="buttons vertical">
											<li>
												<input type="button" name="action" class="add button fixedwidth" value="Add Purchase Order" onclick="window.location.href='<?php echo $edit_page . "?j=" . $masked_jobnumber;?>'">
											</li>
											<li>
												<input type="button" name="action" class="back button fixedwidth" value="back" onclick="window.location.href='<?php echo "dashboard.php?j=" . $masked_jobnumber;?>'">
											</li>
										</ul>
										
									<section class="special_table" >
										<div class="row">
											<div class="11u -1u">

												<!-- Put search stuff here etc -->
													<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="viewoptions">
													<input type="hidden" name="j" value="<?php echo $masked_jobnumber; ?>" />
														<?php  
															echo '<input class="search_box" type="text" name="search" placeholder="Search" value="'.$search. '">';
															echo '<input class="find" type="submit" name="submit" value="Search"/>';
														?>
																	<span class="right">
																	<?php echo "Total of Invoices: $" . money_format("%n",$subtotal);?>
																	</span>										
													</form>

											</div>
										</div>
										

											<div class="sep"></div>

										<!-- Table goes below here -->
										<?php echo $table;?>
										<?php echo "Total of Invoices: $" . money_format("%n",$subtotal);?>

										
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
