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

class FormDateField extends FormField{
    
    //type can be text,textbox, checkbox,radio, pulldown, date, hidden
    
    private $validationString;

    private $size = 30;
    private $id_type = 'datepicker1'; //must have a js associated with id to work
    
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
        $this->type = "date";
        if ($defaultValue = "") {
            $this->defaultValue = date("Y-m-d");
        }
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
        $html .= '<input id="'. $this->id_type  .'" class="text" size="'. $this->size .
                '" name="'. $this->columnName .'" value="'. $this->defaultValue .
                '" readonly="readonly" />';
        
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
    
////////////////////////////////////////////////////////////////////////////
//
// setIDType
//
// Sets the validation criteria for this field
//
// Param:   $string - sets the id for the date - to work correctly this
//                    must hava a valid javascript associated with it
//
// Return:  none
//

    public function setIDType($string){
        $this->idType = $string;
    }
    
}


?>
