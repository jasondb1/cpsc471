<?php
require_once("FormField.php");
/*
 * FormTextField.php
 * 
 * Copyright 2018
 * 
 * This class displays and produces the form data
 * 
 */

class FormTextField extends FormField{
     
    private $validationString;
    
    private $size = 30;
    
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

    function __construct($colName, $displayName, $defaultValue = ""){
        $this->columnName = $colName;
        $this->displayName = $displayName;
        $this->type = "text";
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
        
        //TODO: add readonly parameter
        $html .= '<input class="text" type="'. $this->type    .'" size="'. $this->size .'" name="'. $this->columnName .'" value="'. $this->defaultValue .'"  />';
        return $html;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// validate //TODO: not tested
//
// Validates the field with a regular expression
//
// Param:   $string - the value to match with the regular expression
//                  - usually user input from a form field
//
// Return:  True if string matches the validation rule
//
    
    public function validate($string){
        $validated = false;
            if( preg_match($this->validationString, $string)) {
                $validated = true;
            }
        return $validated;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// setValidation
//
// Sets the validation criteria for this field
//
// Param:   $string - a regular expression for validation criteria 
//
// Return:  none
//

    public function setValidation($string){
        $this->validationString = $string;
    }
    
}


?>
