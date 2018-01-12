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
	
	//$jobnnumber=$_GET['jobnumber'];
	$path=$vi_path;
	$db_table="vendor_invoices";
	$page_title="Vendor Invoices";
	
	//Edit or upload page
	$edit_page="../employee_invoice_upload.php";	
	
	//Sets the columns visible in the table format (column name=>display name) special ("edit,"amount","price","del","delete","filename")
	$columns=array("date_entered"=>"Date","vendor"=>"Vendor","invoice_number"=>"Inv #","amount"=>"Amount","division"=>"Div","status"=>"Status","filename"=>"");

	//color line text if status is ("status value"=>"d0d0d0")
	//$status_color=array("Completed"=>"00ff00,"Outstanding"=>"ff0000","Other"=>"d0d0d0");
	
	
//////////////////////////////////////Search and Order Criteria

			//search function
				 $search = $_GET['search']; 
				if ($search!=""){
					 $where = " WHERE vendor LIKE '%$search%'
								OR notes LIKE '%$search%'
								OR invoice_number LIKE '%$search%'
								OR status LIKE '%$search%'
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
		
			$sql = "SELECT * FROM $db_table" .$where;
				$result = $mysql_link->query($sql) or die($mysql_link->error());
				 $rows = $result->affected_rows;
				 
			 if ($rows == 0) {
				 echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
				echo "<br><br><b><big>No records found</b></big><br><br>";
				echo 	'<input type="Button" value="Enter Document" onclick="location.href=\''. $edit_page . '?j=' . $masked_jobnumber  .'\'">';
				die();
			 }	
			 
/////////////////////////////////////////////////Delete Record
			if ($delete_record!=""){	
				$mysql_link->query("DELETE FROM $db_table WHERE uid='$delete_record'") or die($mysql_link->error());  
		 
				echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
				echo "<br><br><b><big>Successfully Deleted</b></big><br><br>";
				echo 	'<input type="Button" value="Back" onclick="location.href=\''.$_SERVER['PHP_SELF']  .'?j='.$masked_jobnumber  .'\'">';
				die();		
			}//end if delete
		
/////////////////// Get data from table
			$sql = "SELECT * FROM $db_table". $where . $order . $max;

			$result = $mysql_link->query($sql) or die('Could not retrieve data: ' . $mysql_link->error());
			
		//table code
		$table = '<table style="width: 100%;"><tr>';
			//Show Headings
				foreach ($columns as $x=>$y){
					$heading=$y;
					if ($direction =="ASC") {
						$table .= "<th><b><a href=". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=DESC><b>$heading</b></a></b></th>";
					}
					else {
						$table .= "<th><b><a href='". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=ASC'><b>$heading</b></a></b></th>";
					}
				  }
				$table .= '</tr>';
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
						}
						elseif ($i=="hours"){ 
							$table .= sprintf("%01.2f", $row[$i]).'</td>';
						}
						elseif ($i=="delete" || $i=="del"){ 
							//$table .=  '<a href="'.$_SERVER['PHP_SELF'].'?delete_record=' . $row['uid'] . '&j='. $masked_jobnumber . '"><img src="img/i_delete.png" /></a>';
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
			
		$table .='</table>';

	
?>


<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pl" xml:lang="pl">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $page_title; ?></title>
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
				<div class="h_title"><?php echo $page_title; ?></div>
				<h2><?php echo $page_title; ?> :: Jobnumber - <?php echo $jobnumber;?></h2>
				<input type="button" name="action" class="button add" value="Add <?php echo $page_title;?>" onclick="window.location.href='<?php echo $edit_page . "?j=" . $masked_jobnumber;?>'"> <br> 
				<!-- Put search stuff here etc -->
					<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="viewoptions">
						<input type="hidden" name="j" value="<?php echo $masked_jobnumber; ?>" />
						<?php  
							echo '<br><input style="background-color:#FFFFCC; border-style:solid; border-width:medium;" type="text" name="search" placeholder="Search" value="'.$search. '">';
							echo '<input class="search" type="submit" name="submit" value="Search"/>';
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
