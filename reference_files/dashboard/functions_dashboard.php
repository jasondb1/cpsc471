<?php

	//include_once ("cfg_dashboard.php");
$background_color_1 = "#f0f0f0";
$background_color_2 = "#d0d0d0";

	//connect to database
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
			

////////////////////////////////////////////////// Get Employee Names
function getEmployeeNames(){
		require "_config.php";
	//connect to database
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		$sql = "SELECT phpauthent_users.username FROM `employee_info` JOIN `phpauthent_users` ON `phpauthent_users`.id = `employee_info`.uid WHERE employee_info.status='Active'";
		$retval = $mysql_link->query($sql) or die($mysql_link->error);
		while ($row = $retval->fetch_assoc()) {
			$employee_list[]=$row['username'];
		}
		sort ($employee_list);
		return $employee_list;
}
			
			
/////////////////////////////////////// Get info

	$sql = "SELECT * FROM dashboard_project WHERE jobnumber='$jobnumber';";
	
	$retval = $mysql_link->query($sql) or die($mysql_link->error);

	while ($row = $retval->fetch_assoc()) {
		$description=$row['description'];
		$owner=$row['owner'];
		$location=$row['location'];
		$start_date=$row['start_date'];
	}
		
	$jobinfo = "
			<table class=\"small_table\" style='width:100%;'>
					<tr>
						<td>Project Number:</td><td>$jobnumber</td>
					</tr>
					<tr>
						<td>Project Name:</td><td>$description</td>
					</tr>
					<tr>
						<td>Project Owner:</td><td>$owner</td>
					</tr>
					<tr>
						<td>Project Location:</td><td>$location</td>
					</tr>
					<tr>
						<td>Start Date:</td><td>$start_date</td>
					</tr>
					</table>";

/////////////////////////////////////// Get List of Jobs

	$sql = "SELECT * FROM dashboard_project;";
	
	$retval = $mysql_link->query($sql) or die($mysql_link->error);

	while ($row = $retval->fetch_assoc()) {
		$job_list[]=$row['jobnumber'];
	}
		
	$jobselect = '<form method="post" action="'. $_SERVER['PHP_SELF'] .'"><select name="jobnumber"><option></option>';
	foreach ($job_list as $value){
		if ($jobnumber==$value){$jobselect .= '<option selected>'. $value   .'</option>';}
		else {$jobselect .= '<option>'. $value   .'</option>';}
	}
	$jobselect .= '	</select>
					<input class="button.alt" type="submit" name="submit" value="Go" />
					<input type="hidden" name="jobumber" value="'. $jobnumber   .'"/>
					</form>';	
		
/////////////////////////////////////// Purchase Orders
	$where = " WHERE jobnumber LIKE '".$jobnumber   ."%'";
	$order = " ORDER BY date_entered DESC";
	$max = "";
	$sql ="SELECT purchase_order_items.*, purchase_order.vendor, purchase_order.date_received, purchase_order.status, purchase_order.id
	FROM purchase_order_items
	LEFT JOIN purchase_order
	ON purchase_order_items.po_number=purchase_order.po_number
	". $where . $order . $max;

	$po_result = $mysql_link->query($sql) or die('Could not retrieve data: ' . $mysql_link->error());
	
	//Sets the columns visible in the table
	$po_cols=array("po_number"=>"PO Number","division"=>"Div","changeorder"=>"CO","vendor"=>"Vendor","date_entered"=>"Date Entered","price"=>"Price","status"=>"Status");

	//Create Table
	$po_table = '<table class="small_table" ><tr>';
	
	

		//headings
		foreach ($po_cols as $key=>$value){
			$po_table .= "<th>$value</th>";
		}
		//data
		$po_table .= '</tr>';
		$j=1;
		while ($row = $po_result->fetch_assoc()) {
		$id_number = $row['id'];
		
					if ($j % 2 == 0){ 
						$po_table .= '<tr style="background-color: '.$background_color_1.'">'; 
					} 
					else { 
						$po_table .= '<tr style="background-color: '.$background_color_2.'">';
					}
					
			foreach ($po_cols as $key=>$value){
					if ($key=="price"){ 
					//$po_table .= '<td>'.sprintf("$%01.2f", $row[$key]).'</td>';
					$po_table .= '<td>'.money_format("%n", $row[$key]).'</td>';
					$po_amount+=$row['price'];
					}
					elseif ($key=="po_number"){
						$po_table .= '<td><a href="../employee_po_entry.php?id='. $id_number . '">'. $row[$key] .'</a></td>';
					}
					else{
						$po_table .= '<td>'. $row[$key] .'</td>';
					}
					}		
		$po_table .= '</tr>';	
		$j++;		
		}
		$po_table .= '</table>';

/////////////////////////////////////// Change Orders
	$where = " WHERE jobnumber LIKE '".$jobnumber   ."%'";
	$order = " ORDER BY date_entered DESC";
	$max = "";
	$sql ="SELECT changeorder_items.*, changeorder.date_entered, changeorder.status, changeorder.id
	FROM changeorder_items
	LEFT JOIN changeorder
	ON changeorder_items.co_number=changeorder.co_number
	". $where . $order . $max;

	$co_result = $mysql_link->query($sql) or die('Could not retrieve data: ' . $mysql_link->error());
	
	//Sets the columns visible in the table
	$co_cols=array("co_number"=>"CO Number","description"=>"Description","date_entered"=>"Date Entered","status"=>"Status");

	//Create Table
	$co_table = '<table class="small_table" ><tr>';
		//headings
		foreach ($co_cols as $key=>$value){
			$co_table .= "<th>$value</th>";
		}
		//data
		$co_table .= '</tr>';
		$j=0;
		while ($row = $co_result->fetch_assoc()) {
			if ($j % 2 == 0){ 
						$co_table .= '<tr style="background-color: '.$background_color_1.'">'; 
					} 
					else { 
						$co_table .= '<tr style="background-color: '.$background_color_2.'">';
					}
		$id_number = $row['id'];

			foreach ($co_cols as $key=>$value){
					if ($key=="price"){ $co_table .= '<td>'.sprintf("$%01.2f", $row[$key]).'</td>';}
					elseif ($key=="co_number"){
						$co_table .= '<td><a href="../employee_change_order_entry.php?id='. $id_number . '">'. $row[$key] .'</a></td>';
					}
					else{
						$co_table .= '<td>'. $row[$key] .'</td>';
					}
					}		
		$co_table .= '</tr>';	
		++$j;
		}
		$co_table .= '</table>';
		
		
/////////////////////////////////////// RFI 
	$where = " WHERE jobnumber LIKE '".$jobnumber   ."%' AND status<>'Closed'";
	$order = " ORDER BY `rfi_number` ASC";
	$max = "";
	$sql ="SELECT *	FROM request_for_information
	". $where . $order . $max;

	$rfi_result = $mysql_link->query($sql) or die('Could not retrieve data: ' . $mysql_link->error());
	
	//Sets the columns visible in the table
	$rfi_cols=array("rfi_number"=>"RFI Number","description"=>"Description","date"=>"Date Entered","status"=>"Status");

	//Create Table
	$rfi_table = '<table class="small_table" ><tr>';
		//headings
		foreach ($rfi_cols as $key=>$value){
			$rfi_table .= "<th>$value</th>";
		}
		//data
		$rfi_table .= '</tr>';
		$j=0;
		while ($row =$rfi_result->fetch_assoc()) {
		$id_number = $row['id'];
		if ($j % 2 == 0){ 
			$rfi_table .= '<tr style="background-color: '.$background_color_1.'">'; 
			} 
			else { 
				$rfi_table .= '<tr style="background-color: '.$background_color_2.'">';
			}
			foreach ($rfi_cols as $key=>$value){
					if ($key=="price"){ $rfi_table .= '<td>'.sprintf("$%01.2f", $row[$key]).'</td>';}
					elseif ($key=="rfi_number"){
						$rfi_table .= '<td><a href="../employee_rfi_entry.php?id='. $id_number . '">'. $row[$key] .'</a></td>';
					}
					else{
						$rfi_table .= '<td>'. $row[$key] .'</td>';
					}
					}		
		$rfi_table .= '</tr>';
		++$j;		
		}
		$rfi_table .= '</table>';
		
/////////////////////////////////////// Vendor Invoices
	$path="../files/vendor_invoices/";//change later as required
	$where = " WHERE jobnumber LIKE '".$jobnumber   ."%'";   //." AND status<>'Closed'";
	$order = " ORDER BY `invoice_date` DESC";
	$max = "";
	$sql ="SELECT *	FROM vendor_invoices". $where . $order . $max;

	$result = $mysql_link->query($sql) or die('Could not retrieve data: ' . $mysql_link->error());
	
	//Sets the columns visible in the table
	$cols=array("filename"=>"","date_entered"=>"Date","invoice_number"=>"Invoice","division"=>"Division","vendor"=>"Vendor","status"=>"Status","amount"=>"Amount");

	//Create Table
	$inv_table = '<table class="small_table" ><tr>';
		//headings
		foreach ($cols as $key=>$value){
			$inv_table .= "<th>$value</th>";
		}
		//data
		$inv_table .= '</tr>';
		$j=0;
		while ($row = $result->fetch_assoc()) {
			$id_number = $row['uid'];
			if ($j % 2 == 0){ 
				$inv_table .= '<tr style="background-color: '.$background_color_1.'">'; 
			} 
			else { 
				$inv_table .= '<tr style="background-color: '.$background_color_2.'">';
			}
		
			foreach ($cols as $key=>$value){
					if ($key=="amount"){ $inv_table .= '<td>'.sprintf("$%01.2f", $row[$key]).'</td>';
					$total_amount+=$row['amount'];
					}
					// elseif ($key=="rfi_number"){
						// $inv_table .= '<td><a href="../employee_invoice_view.php?id='. $id_number . '">'. $row[$key] .'</a></td>';
					// }
					elseif ($key == "filename"){
					$filename=$row[$key];
					$inv_table .= '<td><a href="'.	$path . $filename.'"><img src="img/doc.png" /></a></td>';
					}
					else{
						$inv_table .= '<td>'. $row[$key] .'</td>';
					}
					}		
			$inv_table .= '</tr>';	
			++$j;
		}
		$inv_table .= '</table>';		
		
/////////////////////////////////////////////Invitation to Bid
		$path=$itb_path;//change later as required
		$where = " WHERE jobnumber LIKE '".$jobnumber   ."%'";   //." AND status<>'Closed'";
		$order = " ORDER BY `date_entered` DESC";
		$max = "";

			$sql = "SELECT * FROM invitation_to_bid". $where . $order . $max;

			$result = $mysql_link->query($sql) or die('Could not retrieve data: ' . $mysql_link->error());
			
				//Sets the columns visible in the table format (column name=>display name)
			$columns=array("edit"=>"Edit","date_entered"=>"Date","date_closing"=>"Closing","vendor"=>"Vendor","division"=>"Division","status"=>"Status","filename"=>"File");
			
		//table code
		$itb_table = '<table class="small_table" style="width: 100%;"><tr>';
			//Show Headings
				foreach ($columns as $x=>$y){
					$heading=$y;
					if ($direction =="ASC") {
						$itb_table .= "<th><a href=". $_SERVER['PHP_SELF']."?jobnumber=$jobnumber&sort_by=$x&direction=DESC>$heading</b></a></b></th>";
					}
					else {
						$itb_table .= "<th><a href='". $_SERVER['PHP_SELF']."?jobnumber=$jobnumber&sort_by=$x&direction=ASC'>$heading</b></a></b></th>";
					}
				  }
				$itb_table .= '</tr>';
			//Output the data		
				$j=1;
				while($row = $result->fetch_assoc())
				{
					// Your while loop here
					// Color odd lines
					if ($j % 2 == 0){ 
						$itb_table .= '<tr style="background-color: '.$background_color_1.'">'; 
					} 
					else { 
						$itb_table .= '<tr style="background-color: '.$background_color_2.'">';
					}
					
					foreach ($columns as $i=>$value){
						$color = "000000";//basic color
							// foreach ($status_color as $status_key=>$color_value){
								// if ($row['status']==$status_key){
								// $color=$color_value;
								// }
							// }
						
						$itb_table .=  '<td style="color:#' . $color . ';">';  
							
				//check if special cell and format set formatting conditions				
						if ($i == "edit") { 
							$id_number = $row['uid'];
							$itb_table .= '<a href="'.$edit_page .'?id='. $id_number . '"><img src="img/i_edit.png" /></a></td>';
						}
						elseif ($i == "filename"){
							$filename=$row[$i];
							$itb_table .= '<a href="'.	$path . $filename.'"><img src="img/doc.png" /></a>';
						}
						elseif ($i=="amount" || $i=="price"){ 
							//$itb_table .= sprintf("$%01.2f", $row[$i]).'</td>';
							$itb_table .= money_format("%n",$row[$i]);
						}
						elseif ($i=="hours"){ 
							$itb_table .= sprintf("%01.2f", $row[$i]).'</td>';
						}
						elseif ($i=="delete" || $i=="del"){ 
							$itb_table .= sprintf("%01.2f", $row[$i]).'</td>';
						}
				//Regular Cell	
						else{ 
							$itb_table .= $row[$i] . "</td>"; 
						}
								
					}//end foreach for column

					// add extra columns (if required)
				
					$itb_table .= '</tr>';
					$j++;
				}//end while
			
		$itb_table .='</table>';		
		
///////////////////////////////////////Site Instruction Table
		$path=$si_path;//change later as required
		$where = " WHERE jobnumber LIKE '".$jobnumber   ."%'";   //." AND status<>'Closed'";
		$order = " ORDER BY `si_date` DESC";
		$max = "";
		
		//Sets the columns visible in the table format (column name=>display name)
		$columns=array("edit"=>"Edit","si_date"=>"Date","si_number"=>"SI Number","description"=>"Description","notes"=>"Notes","status"=>"Status","filename"=>"File");

		$sql = "SELECT * FROM site_instructions". $where . $order . $max;

			$result = $mysql_link->query($sql) or die('Could not retrieve data: ' . $mysql_link->error());
			
		//table code
		$si_table = '<table class="small_table" style="width: 100%;"><tr>';
			//Show Headings
				foreach ($columns as $x=>$y){
					$heading=$y;
					if ($direction =="ASC") {
						$si_table .= "<th><b><a href=". $_SERVER['PHP_SELF']."?jobnumber=$jobnumber&sort_by=$x&direction=DESC><b>$heading</b></a></b></th>";
					}
					else {
						$si_table .= "<th><b><a href='". $_SERVER['PHP_SELF']."?jobnumber=$jobnumber&sort_by=$x&direction=ASC'><b>$heading</b></a></b></th>";
					}
				  }
				$si_table .= '</tr>';
			//Output the data		
				$j=1;
				while($row = $result->fetch_assoc())
				{
					// Color odd lines
					if ($j % 2 == 0){ 
						$si_table .= '<tr style="background-color: '.$background_color_1.'">'; 
					} 
					else { 
						$si_table .= '<tr style="background-color: '.$background_color_2.'">';
					}
					
					foreach ($columns as $i=>$value){
						$color = "000000";//basic color
							// foreach ($status_color as $status_key=>$color_value){
								// if ($row['status']==$status_key){
								// $color=$color_value;
								// }
							// }
						
						$si_table .=  '<td style="color:#' . $color . ';">';  
							
				//check if special cell and format set formatting conditions				
						if ($i == "edit") { 
							$id_number = $row['uid'];
							$si_table .= '<a href="'.$edit_page .'?id='. $id_number . '"><img src="img/i_edit.png" /></a></td>';
						}
						elseif ($i == "filename"){
							$filename=$row[$i];
							$si_table .= '<a href="'.	$path . $filename.'"><img src="img/doc.png" /></a>';
						}
						elseif ($i=="amount" || $i=="price"){ 
							//$si_table .= sprintf("$%01.2f", $row[$i]).'</td>';
							$si_table .= money_format("%n",$row[$i]);
						}
						elseif ($i=="hours"){ 
							$si_table .= sprintf("%01.2f", $row[$i]).'</td>';
						}
						elseif ($i=="delete" || $i=="del"){ 
							$si_table .= sprintf("%01.2f", $row[$i]).'</td>';
						}
				//Regular Cell	
						else{ 
							$si_table .= $row[$i] . "</td>"; 
						}
								
					}//end foreach for column

					// add extra columns (if required)
				
					$si_table .= '</tr>';
					$j++;
				}//end while
			
		$si_table .='</table>';		
	
///////////////////////////////////////File Table
		$path="../files/projects/".$jobnumber."/";//change later as required
		$where = " WHERE jobnumber LIKE '".$jobnumber   ."%'";   //." AND status<>'Closed'";
		$order =" ORDER BY upload_date DESC";
		$max = "";
		
		//Sets the columns visible in the table format (column name=>display name)
	$columns=array("edit"=>"","directory"=>"Directory","description"=>"Description","revision"=>"Rev","owner"=>"Owner","vendor"=>"Vendor","notes"=>"Notes","filename"=>"","del"=>"");

		$sql = "SELECT * FROM filedata". $where . $order . $max;

			$result = $mysql_link->query($sql) or die('Could not retrieve data: ' . $mysql_link->error());
			
		//table code
		$files_table = '<table class="small_table" style="width: 100%;"><tr>';
			//Show Headings
				foreach ($columns as $x=>$y){
					$heading=$y;
					if ($direction =="ASC") {
						$files_table .= "<th><b><a href=". $_SERVER['PHP_SELF']."?jobnumber=$jobnumber&sort_by=$x&direction=DESC><b>$heading</b></a></b></th>";
					}
					else {
						$files_table .= "<th><b><a href='". $_SERVER['PHP_SELF']."?jobnumber=$jobnumber&sort_by=$x&direction=ASC'><b>$heading</b></a></b></th>";
					}
				  }
				$files_table .= '</tr>';
			//Output the data		
				$j=1;
				while($row = $result->fetch_assoc())
				{
					// Color odd lines
					if ($j % 2 == 0){ 
						$files_table .= '<tr style="background-color: '.$background_color_1.'">'; 
					} 
					else { 
						$files_table .= '<tr style="background-color: '.$background_color_2.'">';
					}
					
					foreach ($columns as $i=>$value){
						$color = "000000";//basic color
							// foreach ($status_color as $status_key=>$color_value){
								// if ($row['status']==$status_key){
								// $color=$color_value;
								// }
							// }
						
						$files_table .=  '<td style="color:#' . $color . ';">';  
							
				//check if special cell and format set formatting conditions				
						if ($i == "edit") { 
							$id_number = $row['uid'];
							$files_table .= '<a href="'.$edit_page .'?id='. $id_number . '"><img src="img/i_edit.png" /></a></td>';
						}
						elseif ($i == "filename"){
							$path=$row['path'];
							$filename=$row[$i];
							$files_table .= '<a href="'.	$path . $filename.'"><img src="img/doc.png" /></a>';
						}
						elseif ($i=="amount" || $i=="price"){ 
							//$files_table .= sprintf("$%01.2f", $row[$i]).'</td>';
							$files_table .= money_format("%n",$row[$i]);
						}
						elseif ($i=="hours"){ 
							$files_table .= sprintf("%01.2f", $row[$i]).'</td>';
						}
						elseif ($i=="delete" || $i=="del"){ 
							$files_table .= sprintf("%01.2f", $row[$i]).'</td>';
						}
				//Regular Cell	
						else{ 
							$files_table .= $row[$i] . "</td>"; 
						}
								
					}//end foreach for column

					// add extra columns (if required)
				
					$files_table .= '</tr>';
					$j++;
				}//end while
			
		$files_table .='</table>';		
	
	///////////////////////////////////////Vendor Contracts Table
		$path=$contract_path;
		$where = " WHERE jobnumber LIKE '".$jobnumber   ."%'";   //." AND status<>'Closed'";
		$order =" ORDER BY date_entered DESC";;
		$max = "";
		
			//Sets the columns visible in the table format (column name=>display name)
	$columns=array("edit"=>"","contract_date"=>"Date","contract_number"=>"Contract Number","description"=>"Description","notes"=>"Notes","status"=>"Status","filename"=>"","del"=>"");

		$sql = "SELECT * FROM vendor_contracts". $where . $order . $max;

			$result = $mysql_link->query($sql) or die('Could not retrieve data: ' . $mysql_link->error());
			
		//table code
		$vendor_contracts_table = '<table class="small_table" style="width: 100%;"><tr>';
			//Show Headings
				foreach ($columns as $x=>$y){
					$heading=$y;
					if ($direction =="ASC") {
						$vendor_contracts_table .= "<th><b><a href=". $_SERVER['PHP_SELF']."?jobnumber=$jobnumber&sort_by=$x&direction=DESC><b>$heading</b></a></b></th>";
					}
					else {
						$vendor_contracts_table .= "<th><b><a href='". $_SERVER['PHP_SELF']."?jobnumber=$jobnumber&sort_by=$x&direction=ASC'><b>$heading</b></a></b></th>";
					}
				  }
				$vendor_contracts_table .= '</tr>';
			//Output the data		
				$j=1;
				while($row = $result->fetch_assoc())
				{
					// Color odd lines
					if ($j % 2 == 0){ 
						$vendor_contracts_table .= '<tr style="background-color: '.$background_color_1.'">'; 
					} 
					else { 
						$vendor_contracts_table .= '<tr style="background-color: '.$background_color_2.'">';
					}
					
					foreach ($columns as $i=>$value){
						$color = "000000";//basic color
							// foreach ($status_color as $status_key=>$color_value){
								// if ($row['status']==$status_key){
								// $color=$color_value;
								// }
							// }
						
						$vendor_contracts_table .=  '<td style="color:#' . $color . ';">';  
							
				//check if special cell and format set formatting conditions				
						if ($i == "edit") { 
							$id_number = $row['uid'];
							$vendor_contracts_table .= '<a href="'.$edit_page .'?id='. $id_number . '"><img src="img/i_edit.png" /></a></td>';
						}
						elseif ($i == "filename"){
							$path=$row['path'];
							$filename=$row[$i];
							$vendor_contracts_table .= '<a href="'.	$path . $filename.'"><img src="img/doc.png" /></a>';
						}
						elseif ($i=="amount" || $i=="price"){ 
							//$vendor_contracts_table .= sprintf("$%01.2f", $row[$i]).'</td>';
							$vendor_contracts_table .= money_format("%n",$row[$i]);
						}
						elseif ($i=="hours"){ 
							$vendor_contracts_table .= sprintf("%01.2f", $row[$i]).'</td>';
						}
						elseif ($i=="delete" || $i=="del"){ 
							$vendor_contracts_table .= sprintf("%01.2f", $row[$i]).'</td>';
						}
				//Regular Cell	
						else{ 
							$vendor_contracts_table .= $row[$i] . "</td>"; 
						}
								
					}//end foreach for column

					// add extra columns (if required)
				
					$vendor_contracts_table .= '</tr>';
					$j++;
				}//end while
			
		$vendor_contracts_table .='</table>';		
	
	//get employee hours
		$sql = "SELECT * FROM timelog WHERE jobnumber='$jobnumber' ORDER BY `date`";
		$hours = $mysql_link->query($sql) or die('Could not retrieve data: ' . $mysql_link->error());
		$total_hours="";
		$hour_breakdown = "";
		$employee_hours="";
		$comment_display = '<table class="small_table">';
		$comment_display .= '<thead><tr>		<th>Date</th>		<th>Employee</th>		<th>Hours</th>		<th>Comment</th>		</tr></thead>';
		$j=1;
		while ($row = $hours->fetch_assoc()) {
			$total_hours += $row['hours'];
			$employee=$row['employee'];
			$employee_hours[$employee] += $row['hours'];
			$date_temp = $row['date'];
			$comment_temp = $row['comment'];
			$employee_temp = $row['employee'];
			if ($j % 2 == 0){ 
						$comment_display .= '<tr style="background-color: '.$background_color_1.'">'; 
					} 
					else { 
						$comment_display .= '<tr style="background-color: '.$background_color_2.'">';
					}
				$comment_display .= "<td>" . $date_temp . "</td><td>".$employee_temp."</td><td>".$row['hours'] ."</td><td>" . $comment_temp . "</td></tr>";
			++$j;
		}
		$comment_display .= "</table>";
		
		//print_r($employee_hours);
		if ($employee_hours!=""){
			
			$hour_breakdown = '<table class="small_table" >';
			$comment_display .= '<thead><tr>		<th>Employee</th>		<th>Hours</th>';
			foreach ($employee_hours as $key=>$value){
				//$hour_breakdown .= $key . " - " . $value. "<br>";
				$hour_breakdown .= "<tr><td>" . $key . "</td><td>" . $value . "</td></tr>";
			}
			$hour_breakdown .= "</table>";
		}
	
?>
