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

class FormHidden extends FormField{
    
    private $validationString;
    
    private $requireValidation = false;
    
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

    function __construct($colName, $displayName, $table, $defaultValue = ""){
        $this->columnName = $colName;
        $this->displayName = $displayName;
        $this->type = "hidden";
        $this->defaultValue = $defaultValue;
        $this->table = $table;
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
        $html = '<input class="text" type="hidden" name="'. $this->columnName .'" value="'. $this->defaultValue .'"  />';
        
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
