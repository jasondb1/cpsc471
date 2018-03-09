<?php
require_once("FormField.php");
/*
 * FormTextBox.php
 * 
 * Copyright 2018
 * 
 * This class displays and produces the form data
 * 
 */

class FormCheckbox extends FormField{
    
    private $validationString;
    
////////////////////////////////////////////////////////////////////////////
//
// __construct
//
// Object constructor
//
// Param:   $colName - the name corresponding to the database column name
//          $displayName - the name to display for the field
//          $defaultValue - default value to set for field
//
// Return:  none
//

    function __construct($colName, $displayName, $defaultValue = "1"){
        $this->columnName = $colName;
        $this->displayName = $displayName;
        $this->type = "checkbox";
        $this->defaultValue = $defaultValue;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// toHtml
//
// Opens the database to perform operations
//
// Param:   none
//
// Return:  a string of html for this field
//

    public function toHtml(){
        $html = '<label>';
        if ($this->isRequired) {$html .= "*";}
        $html .= $this->displayName. ':</label>';
        $html .= '<input class="text" type="checkbox" name="'. $this->columnName .'" value="'. $this->defaultValue .'"  />';
        
        return $html;
    }
       
}


?>
