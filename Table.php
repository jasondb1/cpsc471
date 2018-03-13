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

    private $DEBUG = true;
    public $title;
    public $columns;// column format array(array ('columnName'=>'<name>', 'displayName'=>'<disp name>', 'type'=>'<type>')
    public $enableEdit = false; //adds and edit column
    public $enableDelete = false; //adds a delete column
    public $orderByCol = "";
    public $orderDirection = "DESC";
    public $filter = "";
    public $dataTable="";
    public $max="";
    public $database = null;
    public $editPage = "";
    
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


    function __construct(){
    
    }

////////////////////////////////////////////////////////////////////////////
//
// setdb
//
// Sets the database for where the table data is 
//
// Param:    &$dbobj - a reference to the database object
//
// Return: a string of html that contains the form code
//

   public function setdb(&$dbobj){
        $this->database =& $dbobj;
    }

////////////////////////////////////////////////////////////////////////////
//
// dataFromSQL
//
// Retrieves sql data
//
// Param:    $sql - the sql query to execute - if no value given, the default will look for column values givne
//                                              in only one table
//
// Return: none
//

    private function dataFromSQL($sql = null){
        //TODO error conditions if no table given/exists, columns, etc
        $this->database->opendb();

        if ($sql == null){
            if ($this->orderByCol == ''){
                $this->orderByCol = $this->columns[0]['columnName'];
            }
            $sql = "SELECT * FROM ". $this->dataTable ." WHERE ". $this->filter . " ORDER BY " . $this->orderByCol. " ". $this->orderDirection . $this->max;
        }

        if ($this->DEBUG) {echo $sql;} //var_dump($link);}
        
        $this->queryData = $this->database->getlink()->query($sql);

    }

////////////////////////////////////////////////////////////////////////////
//
// toHTML
//
// outputs the html code for the table
//
// Param:  none
//
// Return: none
//

    public function toHTML($data = null){

    if ($data == null){
        $this->dataFromSQL();
    }

    //TODO: expand this to go to edit page to enter something for table or other
    if ($this->queryData == null){
        echo "No table data exists";
    }

    //Primary key is used for editing and deleting values
    $primaryKey = $this->database->getPrimaryKey($this->dataTable);

    //TODO: error if columns are not given
    //TODO: move styles out, width, color to class variables etc
    
        //table head
        $html = '<b>'. $this->title .'</b>';
		$html .= '<table id="dbtable" style="width: 100%;"><tr>';
            
        //Show Headings
        foreach ($this->columns as $column){
            if ($this->orderDirection =="ASC") {
                $html.= "<th><b><a style='color:#000;' href=". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=". $column['columnName'] ."&direction=DESC>". $column['displayName'] ."</a></b></th>\n";
            }
            else {
                $html .= "<th><b><a style='color:#000;' href='". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=". $column['columnName'] ."&direction=ASC'>". $column['displayName'] ."</a></b></th>\n";
            }
        }
            
        //add edit and delete headings if enabled
        if ($this->enableEdit){
            $html.= "<th><b>Edit</b></th>";
        }
        if ($this->enableDelete){
            $html.= "<th><b>Del</b></th>";
        }
        $html .= '</tr>';
            
        //Output the table data		
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
            foreach ($this->columns as $column){
                $colType = $column['type'];
                $colName = $column['columnName'];
                
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
                if ($colType == "filename"){
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
            }//end foreach

            // add extra columns (if required)	            
            if ($this->enableEdit){
                $editRecordNumber = $row[$primaryKey];
                $html .=  '<td style="color:#' . $color . ';">';  
                $html .= '<a href="'.$this->editPage .'?edit_record='. $editRecordNumber . '">
                <span style="color:#092;">
                <i class="fa fa-edit fa-lg fa-fw"></i>
                </span></a></td>';
            }
            
            if ($this->enableDelete){
                $html .=  '<td style="color:#' . $color . ';">';  
                $html .=  '<a href="'.$_SERVER['PHP_SELF'].'?delete_record=' . $row[$primaryKey] . '&j='. $masked_jobnumber . 
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
    
////////////////////////////////////////////////////////////////////////////
//
// setViewOptions //TODO
//
// Sets the default view options for the User
//
// Param:  none
//
// Return: none
//
    public function setViewOptions(){
        //enter into database
        //if null set columns to defaults
        //store in db with user, table, cols, status?
    }
    
////////////////////////////////////////////////////////////////////////////
//
// getViewOptions //TODO
//
// Retrieves the view options for the user
//
// Param:   none
//
// Return:  none
//
    public function getViewOptions(){
    }
    
}


?>
