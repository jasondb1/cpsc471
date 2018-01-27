/*
 * Table.php
 * 
 * Copyright 2018
 * 
 * This class 
 * 
 */
 
<?php

class Table {

    private $title;
    public  $colDisplay; //array('col1', 'col2') etc
    public  $colType; //array('text', 'number') etc
                      //valid types are text, currency, 
    public  $colName; //name of each column in array defaults filed names from database
    private $data;
    private $hasEdit = false; //adds and edit column
    private $hasDelete = false; //adds a delete column
    
    private $opt_alt_colors = true; //alternate colors of table
    
    
    private $col1 = rgb(255,255,255);
    private $col2 = rgb(255,255,255);
    private $col3 = rgb(255,255,255);
    
    private $sql = "";
    
    
    
    
    //$data is a 2d array in the form
    array(  array('col1'=>'val1', 'col2'=>'val2', 'col2'=>3), //row 1
            array('col1'=>'val1', 'col2'=>'val2', 'col2'=>3), //row 2
            array('col1'=>'val1', 'col2'=>'val2', 'col2'=>3)  //row 3
            )


/*
 * 
 * name: Database::__construct
 * @param
 * @return
 * 
 */
    function __construct(){
    
    }
    
    

    function dataFromSQL{
    
    }

    function toHTML(){
    
    }
    


}


?>
