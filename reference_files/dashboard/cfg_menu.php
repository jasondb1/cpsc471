
			<div class="box">
				<div class="h_title">&#8250; <a href="dashboard.php">Dashboard</a></div>
				<ul id="home">
					<li class="b1"><big>Jobnumber:<b><?php echo $jobnumber; ?></b></big></li>
				
				</ul>
			</div>
				
			<div class="box">
				<div class="h_title">&#8250; Construction Documents</div>
				<ul>
					<li class="b1"><a class="icon page" href="">Purchase Orders (UC)</a></li>
					<li class="b2"><a class="icon page" href="request_for_information.php?j=<?php echo $masked_jobnumber; ?>">RFI's</a></li>
					<li class="b2"><a class="icon page" href="">PCN's (UC)</a></li>
					<li class="b1"><a class="icon page" href="view_files.php?j=<?php echo $masked_jobnumber; ?>">View Files</a></li>
					<li class="b2"><a class="icon page" href="">Daily Reports (UC)</a></li>
					<li class="b2"><a class="icon page" href="">Weekly Reports (UC)</a></li>
					<li class="b1"><a class="icon page" href="">Hours Summary (UC)</a></li>
					<li class="b2"><a class="icon page" href="">Schedule (UC)</a></li>
					<li class="b1"><a class="icon page" href="">Shop Drawings (UC)</a></li>
					<li class="b1"><a class="icon page" href="view_site_instruction.php?j=<?php echo $masked_jobnumber;?>">Site Instructions</a></li>
					<li class="b1"><a class="icon page" href="">Contact List (UC)</a></li>
				</ul>
			</div>
			<div class="box">
				<div class="h_title">&#8250; PM Documents</div>
				<ul>
					<li class="b1"><a class="icon page" href="view_itb.php?j=<?php echo $masked_jobnumber;?>">Invitation to Bid</a></li>
					<li class="b2"><a class="icon page" href="view_vendor_contracts.php?j=<?php echo $masked_jobnumber; ?>">Vendor Contracts</a></li>
					<li class="b1"><a class="icon page" href="">Change Orders</a></li>
					<li class="b2"><a class="icon page" href="">Customer Invoices (UC)</a></li>
					<li class="b1"><a class="icon page" href="">Meeting Minutes (UC)</a></li>
					<li class="b2"><a class="icon page" href="view_vendor_invoices.php?j=<?php echo $masked_jobnumber; ?>">Vendor Invoices</a></li>

				</ul>
			</div>
			
			<div class="box">
				<div class="h_title">&#8250; Reports</div>
				<ul>
					<li class="b1"><a class="icon page" href="view_report_invoices.php?j=<?php echo $masked_jobnumber; ?>">Vendor Invoices Report</a></li>
	
				</ul>
			</div>
			
			<div class="box">
				<div class="h_title">&#8250; Project</div>
				<ul>
					<li class="b1"><a class="icon users" href="">Edit Parameters Summary (UC)</a></li>
					<li class="b2"><a class="icon add_user" href="">Map (UC)</a></li>
					<li class="b1"><a class="icon block_users" href="">Contact List (UC)</a></li>
				</ul>
			</div>
			<div class="box">
				<div class="h_title">&#8250; Administrator</div>
				<ul>
					<li class="b1"><a class="icon config" href="new_project.php">New Project</a></li>
					<li class="b2"><a class="icon contact" href="">Contact Form (UC)</a></li>
					<li class="b1"><a class="icon config" href="">Add Contractor (UC)</a></li>
					<li class="b2"><a class="icon config" href="">User Settings (UC)</a></li>
				</ul>
			</div>
