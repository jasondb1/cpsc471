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
	
	//get parameters
	
	//connect to database
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
	
	//get jobnumber
	$masked_jobnumber = $_GET['j'];
	$jobnumber = xor_this($masked_jobnumber);
	
	$search = $_GET['search']; 
	if ($search!=""){
     $where = " WHERE jobnumber LIKE '%$search%'
				OR notes LIKE '%$search%'
				OR rfi_number LIKE '%$search%'
				OR rfi_to LIKE '%$search%'
				OR rfi_cc LIKE '%$search%'
				OR rfi_from LIKE '%$search%'
				OR concern LIKE '%$search%'
				OR response LIKE '%$search%'
				OR description LIKE '%$search%'
			";
	}
	else{
		$where = " WHERE jobnumber LIKE '". $jobnumber ."%'";
	}

	$order_by = $_GET['sort_by'];
	$direction = $_GET['direction'];

	if ($order_by != ""){
		 $order = " ORDER BY $order_by $direction";
	}
	else{
			$order =" ORDER BY rfi_number DESC";
	}
	
	
	/////////////////////////////////////// RFI 
	$max = "";
	$sql ="SELECT *	FROM request_for_information". $where . $order . $max;
	$result = $mysql_link->query($sql) or die('Could not retrieve data: ' . $mysql_link->error);
	
	//Sets the columns visible in the table
	$columns=array("rfi_number"=>"RFI Number","date"=>"Date Entered","description"=>"Description","concern"=>"Concern","rfi_from"=>"From","rfi_to"=>"To","status"=>"Status");

//Create Table
	$table = '<table style="clear:both; width:100%;"><tr>';
	//Show Headings
	foreach ($columns as $x=>$y){
		$heading=$y;
		if ($direction =="ASC") {$table .= "<th><b><a href=". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=DESC&pagenum=1><b>$heading</b></a></b></th>";}
		else {$table .= "<th><b><a href=". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=ASC&pagenum=1><b>$heading</b></a></b></th>";}
	  }
	$table .= '</tr>';
		$j=1;	
			while($row = $result->fetch_assoc())
			{
				// Your while loop here
				if ($j % 2 == 0){ $table .= '<tr style="background-color: #f0f0f0;">'; } else { $table .= '<tr style="background-color: #e0e0e0;">';}//color odd colored lines	
						foreach ($columns as $i=>$nowrap){
							$color = "000000";	
							$table .=  '<td style="color:#' . $color . ';">';  		
							if ($i == "rfi_number") { 
								$id_number = $row['id'];
								//$table .= "<a href=\"employee_po_entry.php?id=$row['id']\">$row[$i]</a></td>";
								$table .= '<a href="employee_rfi_entry.php?id='. $id_number . '">'. $row[$i] .'</a></td>';
							} 
							elseif ($i == "filename"){
						$filename=$row[$i];
						$table .= '<a href="'.$filename.'"><img src="images/doc.png" /></a>';
						}
						else{ 
							$table .= $row[$i] . "</td>"; 
						}	
						}//end foreach for column		
				$table .= '</tr>';
				$j++;
			}//end while

		$table .= '</table>';

	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pl" xml:lang="pl">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Request For Information</title>
<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/navi.css" media="screen" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
$(function(){
	$(".box .h_title").not(this).next("ul").hide("normal");
	$(".box .h_title").not(this).next("#home").show("normal");
	$(".box").children(".h_title").click( function() { $(this).next("ul").slideToggle(); });
});
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
		
		
		<!-- //Main Area -->
		<div id="main">
			<div class="full_w">
				<div class="h_title">Request For Information</div>
				<?php echo $table;?>
					



			</div>		
		</div>
	</div>
			
			<div class="clear"></div>
</div>

</body>
</html>
