<?php
/*
 * Table.php
 * 
 * Copyright 2018
 * 
 * This class formats tables generated from SQL queries
 * 
 */
ini_set('display_errors',0); 
error_reporting(0);
require_once('Database.php');

class Table{

    public $title;
    public $arrayColDisplayName; //array('col1', 'col2') etc
    public $arrayColType; //array('text', 'number') etc
                            //valid types are text, currency, 
    public $arrayColName; //name of each column in array defaults filed names from database
    public $enableEdit = false; //adds and edit column
    public $enableDelete = false; //adds a delete column
    public $orderByCol = "";
    public $orderDirection = "DESC";
    public $filter = "";
    public $dataTable="";
    public $max="";
    public $database = null;
    
    private $optAltColors = true; //alternate colors of table
    
    private $color1 = "#000000";
    private $color2 = "#f0f0f0";
    private $color3 = "#e0e0e0";
    
    private $sql = "";
    private $queryData = "";
    
    //$arrayData is a 2d array in the form
    //array(  array('col1'=>'val1', 'col2'=>'val2', 'col2'=>3), //row 1
    //        array('col1'=>'val1', 'col2'=>'val2', 'col2'=>3), //row 2
    //        array('col1'=>'val1', 'col2'=>'val2', 'col2'=>3)  //row 3
    //        )


/*
 * 
 * name: Database::__construct
 * @param
 * @return
 * 
 */
    function __construct(){
    
    }
    
   public function setdb(&$dbobj){
        $this->database =& $dbobj;
    }
    
    private function dataFromSQL(){
        //TODO error conditions if no table given/exists, columns, etc
        $this->database->opendb();
        $sql = "SELECT * FROM ". $this->dataTable ." WHERE ". $this->filter . " ORDER BY " . $this->orderByCol. " ". $this->orderDirection . $this->max;
        //echo $sql;
        //$link = $this->database->getlink();
        //var_dump($link);
        $this->queryData = $this->database->getlink()->query($sql);

    }

    public function toHTML(){
        $this->dataFromSQL();
    //TODO: error if columns are not given
    //TODO: move styles out, width, color to class variables etc
    
        //table head
		$html = '<table id="dbtable" style="width: 100%;"><tr>';
            //Show Headings
            foreach ($this->arrayColDisplayName as $y){
                if ($this->orderDirection =="ASC") {
                    $html.= "<th><b><a style='color:#000;' href=". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=DESC>$y</a></b></th>";
                }
                else {
                    $html .= "<th><b><a style='color:#000;' href='". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=ASC'>$y</a></b></th>";
                }
              }
                if ($this->enableEdit){
                    $html.= "<th><b>Edit</b></th>";
                }
                if ($this->enableDelete){
                    $html.= "<th><b>Del</b></th>";
                }
            $html .= '</tr>';
            
            //Output the data		
            $j=1;
            //process each row of the values
            while($row = $this->queryData->fetch_assoc()) {

                // Color odd lines
                if ($j % 2 == 0 && $this->optAltColors){ 
                    $html .= '<tr style="background-color: '. $this->color2. ';">'; 
                } 
                else { 
                    $html .= '<tr style="background-color:'. $this->color3 . ' ;">';
                }
                
                //output and format each cell
                $i = 0;
                while ($i < count($this->arrayColName)) {
                    $colType = $this->arrayColType[$i];
                    $colName = $this->arrayColName[$i];
                    
                    //color code rows based on status
                    //TODO: move this to any arbitrary row value combination
                    if ($row ['status'] == "Completed" && $row ['invoice_number'] !="" ){ $color = "0000ff";} 
                        elseif ($row ['status'] == "Completed" ){ $color = "ff0000";} 
                        elseif ($row ['status'] == "Recurring" ){ $color = "800080";} 
                        elseif ($row ['status'] == "Pending" ){ $color = "707070";} 
                        elseif ($row ['status'] == "On Hold" ){ $color = "707070";} 
                        elseif ($row ['status'] == "In Progress" ){ $color = "000000";} 
                    else  { $color = "000000";}	
                    
                    //output individual cells
                    $html .=  '<td style="color:#' . $color . ';">';  
                
                    //check if special cell and format set formatting conditions
                    //TODO: fix types, add delete and edit types based on enabled				
                    if ($colType == "wo") { 
                            $colNamed_number = $row['jobnumber'];
                            $html .= '<a href="employee_workorder_entry.php?edit_record='. $colNamed_number . '">
                            <span style="color:#092;">
                            <i class="fa fa-flag fa-fw"></i>
                            </span></a></td>';
                    }
                    elseif ($colType == "filename"){
                        $filename=$row[$colName];
                        $html .= '<a href="'.	$path . $filename.'"><span style="color:#00f;"><i class="fa fa-file-o fa-fw"></i></span></a>';
                    }
                    elseif ($colType=="amount" || $colType =="price"){ 
                        $html .= money_format("%n",$row[$colName]);
                    }
                    elseif ($colType=="hours"){ 
                        $html.= sprintf("%01.2f", $row[$colName]).'</td>';
                    }
                    elseif ($colType == "time_in" or $colName == "time_out") {
                        $html .= date ("H:i",strtotime($row[$colName]));  
                    }
                //Regular Cell	
                    else{ 
                        $html .= $row[$colName] . "</td>"; 
                    }	
                    
                $i++;
                }//end while column

                // add extra columns (if required)	
                //TODO: get rid of hardcoded field, dates, others(search)? are passed in order to get
                // back to page/screen we came from
                
                if ($this->enableEdit){
                    $editRecordNumber = $row['jobnumber'];
                    $html .=  '<td style="color:#' . $color . ';">';  
                    $html .= '<a href="'.$edit_page .'?edit_record='. $editRecordNumber . '">
                    <span style="color:#092;">
                    <i class="fa fa-edit fa-lg fa-fw"></i>
                    </span></a></td>';
                }
                
                if ($this->enableDelete){
                    $html .=  '<td style="color:#' . $color . ';">';  
                    $html .=  '<a href="'.$_SERVER['PHP_SELF'].'?delete_record=' . $row['jobnumber'] . '&j='. $masked_jobnumber . 
                    '&date_current='. $date_current.'&date_end='.$date_end.
                    '&employee='.$employee.
                    //confirmation dialog
                    '" onclick="return confirm(\'Confirm Delete?\')" ><span style="color:#f00;"><i class="fa fa-minus-circle fa-fw"></i></span></a>';
                }
                
                $html .= '</tr>';
                $j++;
            }//end while
			
		$html .='</table>';  
        return $html;  
    }
    
    public function setViewOptions(){
        //enter into database
        //if null set columns to defaults
        //store in db with user, table, cols, status?
    }
    
    public function getViewOptions(){
    }
    
}


?>
