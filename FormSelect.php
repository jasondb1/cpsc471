<?php
require_once("FormField.php");
/*
 * FormSelect.php
 * 
 * Copyright 2018
 * 
 * This class displays and produces the form data
 * 
 */

class FormSelect extends FormField{
    
    private $validationString;
    private $options; //$options is array of( name=>value, ... )
    
    
////////////////////////////////////////////////////////////////////////////
//
// __construct
//
// Object constructor
//
// Param:   $colName - the name corresponding to the database column name
//          $displayName - the name to display for the field
//          $options - an array of options to select from 
//          $defaultValue - default value to set for field
//
// Return:  none
//

    function __construct($colName, $displayName, $options, $default = ""){
        $this->columnName = $colName;
        $this->displayName = $displayName;
        $this->options = $options;
        $this->type = "select";
        $this->defaultValue = $default;
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
        
        $html .= '<select name="'. $this->columnName  .'">';
        $html .= '<option></option>';
        
        foreach ($this->options as $display=>$value){
            $html .= '<option';
            $html .= ' value="'. $value .'" ';
            if ($this->defaultValue == $value){
                $html .= " selected>";
            } 
            else {
                $html .= ">";
            }
            $html .= $display;
            $html .= '</option>';
        }
        $html .= '</select>';
        
        return $html;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// setOptions
//
// Sets the options displayed in the select field
//
// Param:   $array- array of options
//
// Return:  none
//
    public function setOptions($array){
        $this->options = $array;
    }
    
}


?>
