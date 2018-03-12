<fieldset>
											<legend>Items</legend>
											<div style="float:clear;"></div>
											<div id="dynamicInput">
											<?php 
													$i=0;
													$var_count= count($description);
													if ($var_count<1){$var_count=1;}
													while($i<$var_count)
													{
														echo '<b>Item '. ($i + 1)  .'</b><br>';
														echo '<div id="form_data">';
														echo '<label>*Description:</label>';
														echo '<input class="text" name="description[]" type="text" placeholder="Description" size="20" value="'. $description[$i]. '">';
														echo '<label>Qty:</label>';
														echo '<input class="text" name="quantity[]" type="text" placeholder="Qty" size="4" value="'. $quantity[$i] .'">';
														echo '<label>Est Total:$</label>';		
														echo '<input class="text" name="price[]" type="text" placeholder="Est Total" size="10" value="'.$price[$i] .'">';
														echo '<div style="clear:both;"></div>';
														echo '<label>Division:</label>';

														$select = '<select id="sel_div" name="division[]">';
														$select .= '<option value="">Select Division...</option>';
														foreach ($divisions as $key=>$value){
															$select .= '<option value="'. $key . '"';
															if( $key == $division[$i]){ $select.= ' selected>';} else {$select.= '>';}
															$select .= $value;
															$select .= '</option>';
														}
														$select .= '</select>';
														echo $select;
														echo '<label>Sub-Div:</label>';	
														echo '<input class="text" name="sub_division[]" type="text" placeholder="Sub-Div" size="6" value="'. $sub_division[$i]. '">';
														echo '<div style="clear:both;"></div>';
														echo '</div>';
														if ($i>0 || $var_count>1){
															//echo '<a style="float:right;" href="'. $_SERVER['PHP_SELF']  .'?remove_item='.$item_id[$i] . '&id='. $id  .'">Remove<img src="images/delete.png" /></a>';
															echo '<a style="float:right;" href="'. $_SERVER['PHP_SELF']  .'?remove_item='.$item_id[$i] . '&id='. $id  .'&refer_page='.$refer_page.'"><img src="images/remove.png" />Remove Item</a>';
															}
														echo '<br><hr>';
														$i++;
											}//end while
											?>
											</div>
											<span id="writeroot"></span>
											<input class="submit" type="button" value="Add Another Item" onClick="addInput('dynamicInput');"><br>
											<input class="submit" value="Submit" type="submit" name="submit">
											<script>
												<?php echo 'var counter = ' . $var_count .';';  ?>
												var limit = 20;
												var formhtml = '<label>*Description:</label>';
														formhtml += '<input class="text" name="description[]" type="text" placeholder="Description" size="20" value="">';
														formhtml += '<label>Qty:</label>';
														formhtml += '<input class="text" name="quantity[]" type="text" placeholder="Qty" size="4" value="">';
														formhtml += '<label>Est Total:$</label>';		
														formhtml += '<input class="text" name="price[]" type="text" placeholder="Est Total" size="10" value="">';
														formhtml += '<div style="clear:both;"></div>';
														formhtml += '<label>Division:</label>';
														<?php
														echo "formhtml  += '<select id=\"sel_div\" name=\"division[]\">';\n";
														echo "formhtml  += '<option value=\"\">Select Division...</option>';\n";
														foreach ($divisions as $key=>$value){
															$val = str_replace("'", "\\'", $value);
															echo "formhtml  += '<option value=\"$key\">$val</option>';\n";
														}
														echo "formhtml  += '</select>';\n";
														?>
														formhtml += '<label>Sub-Div:</label>';
														formhtml += '<input class="text" name="sub_division[]" type="text" placeholder="Sub-Div" size="6" value="">';
														formhtml += '<div style="clear:both;"></div>';

												function addInput(divName){
													 if (counter == limit)  {
														  alert("You have reached the limit of adding " + counter + " items");
													 }
													 else { 
												  
													var div1 = document.createElement('div');  
												  
													// Get template data  
													div1.innerHTML = '<b>Item ' + (counter + 1) + '</b><br>';
													div1.innerHTML += formhtml; 
													div1.innerHTML += '<br><hr>'; 		
												  
													// append to our form, so that template data  
													//become part of form  
													document.getElementById(divName).appendChild(div1);  
													counter++;
													 }
											}
											</script>

											<div style="float:clear;"></div><br>
										</fieldset>
