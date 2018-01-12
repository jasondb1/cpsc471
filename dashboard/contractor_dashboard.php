<?php
require_once("../phpauthent/phpauthent_core.php");
require_once("../phpauthent/phpauthent_config.php");

/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/
	$usersArray = array("administrator");
	$groupsArray = array("admin","employee","payroll","supervisor","contractor");
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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pl" xml:lang="pl">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Contractor Dashboard</title>
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
				Main Dashboard
			</div>
			<div class="right">
				<div class="align-right">
					<!--<p>Last login: <strong>(Login Date)</strong></p>-->
					<a href="./_employee_menu.php">Main Menu</a>
				</div>
			</div>
		</div>

		<div id="nav">
		<ul>
			<li class="upp">
				The Dashboard
			</li>
				<div class="align-right">
				<!--<p>Last login: <strong>(Login Date)</strong></p>-->
				<?php echo $jobselect; ?>
				</div>
		
		</ul>

		</div>

	</div>
	
	<div id="content">
		<?php
			if ($jobnumber==0 || $jobnumber==""){
				echo "No Jobnumber Given. Please Select Jobnumber.<br><br>";
				echo $jobselect;
				die();
			}
		?>
		<div id="sidebar">
			<?php include ("cfg_menu.php");?>
		</div>
		
		<!-- //Main Area -->
		<div id="main">
			<div class="half_w half_left">
				<div class="h_title">Info Summary</div>
				<?php echo $jobinfo;?>
			</div>
			
			<div class="half_w half_right" >
				<div class="h_title"><a href="view_files.php?j=<?php echo $masked_jobnumber; ?>">Project Files</a></div>
					<div style="overflow-y: auto; height:150px;" >
					<?php echo $files_table; ?>
					</div>
			</div>
				
				<!-- //Row 2 -->
				<div class="clear"></div>
				
				<div class="half_w half_left" >
				<div class="h_title"><a href="view_itb.php?j=<?php echo $masked_jobnumber; ?>">Invitation To Bid</a>
						<div class="right">
							<div class="align-right">
								
							</div>
						</div>
				</div>
					<div style="overflow-y: auto; height:150px;" >
					<?php echo $itb_table; ?>
					</div>
			</div>
			<div class="half_w half_right" >
				<div class="h_title"><a href="view_change_orders.php?j=<?php echo $masked_jobnumber; ?>">Change Orders</a></div>
					<div style="overflow-y: auto; height:150px;" >
					<?php echo $co_table; ?>
					</div>
			</div>
			
			<!-- //Row 3 -->
			<div class="clear"></div>
				
			<div class="half_w half_left" >
				<div class="h_title"><a href="view_rfi.php?j=<?php echo $masked_jobnumber; ?>">Request For Information</a></div>
					<div style="overflow-y: auto; height:150px;" >
					<?php echo $rfi_table; ?>
					</div>
			</div>
			<div class="half_w half_right">
				<div class="h_title"><a href="view_site_instruction.php?j=<?php echo $masked_jobnumber; ?>">Site Instructions</div>
					<div style="overflow-y: auto; height:150px;" >
					<?php echo $si_table; ?>
					</div>
			</div>

<!-- //Row 4 -->
				<div class="clear"></div>
				
			<div class="half_w half_left" >
				<div class="h_title"><a href="view_purchase_orders.php?j=<?php echo $masked_jobnumber; ?>">Purchase Orders</a>
						<div class="right">
							<div class="align-right">
								Total: <?php echo money_format("%n",$po_amount);?>
							</div>
						</div>
				</div>
					<div style="overflow-y: auto; height:150px;" >
					<?php echo $po_table; ?>
					</div>
			</div>
			<div class="half_w half_right">
					<div class="h_title"><a href="view_vendor_invoices.php?j=<?php echo $masked_jobnumber; ?>">Vendor Invoices</a>
						<div class="right">
							<div class="align-right">
								Total: <?php echo money_format("%n",$total_amount);?>
							</div>
						</div>
					</div>
					<div style="overflow-y: auto; height:150px;" >
					<?php echo $inv_table; ?>
					</div>
			</div>

			<!-- //Row 5 -->
				<div class="clear"></div>
				
			<div class="half_w half_left" >
				<div class="h_title"><a href="view_files.php?j=<?php echo $masked_jobnumber; ?>">Project Files</a></div>
					<div style="overflow-y: auto; height:150px;" >
					<?php echo $files_table; ?>
					</div>
			</div>
			<div class="half_w half_right">
				<div class="h_title"><a href="view_vendor_contracts.php?j=<?php echo $masked_jobnumber; ?>">Vendor Contracts</a></div>
				<div style="overflow-y: auto; height:150px;" >
				<?php echo $vendor_contracts_table; ?>
				</div>
			</div>
			
			<!-- //Row 6 -->
			<div class="clear"></div>
				
			<div class="half_w half_left" >
				<div class="h_title"><a href="view_files.php?j=<?php echo $masked_jobnumber; ?>">Shop Drawings</a></div>
					<div style="overflow-y: auto; height:150px;" >
					<?php echo $shop_drawing_table; ?>
					</div>
			</div>
			<div class="half_w half_right">
				<div class="h_title"><a href="view_vendor_contracts.php?j=<?php echo $masked_jobnumber; ?>">Reserved</a></div>
				<div style="overflow-y: auto; height:150px;" >
				<?php //echo $vendor_contracts_table; ?>
				</div>
			</div>

			
			<!-- //add next row above -->
			</div>		
			</div>
			
			<div class="clear"></div>
			

</body>
</html>
