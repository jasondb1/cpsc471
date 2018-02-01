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

class FormTextBox extends FormField{
    
    //type can be text,textbox, checkbox,radio, pulldown, date, hidden
    
    private $validationString;
    private $defaultValue;
    private $groupName;
    
    private $requireValidation = false;
    private $size = 30;
    
    function __construct($colName, $displayName){
    $this->columnName = $colName;
    $this->displayName = $displayName;
    $this->type = "text";
    }
    
    public function toHtml(){
    $html = '<label>';
    if ($this->requireValidation) {$html .= "*";}
    $html .= $this->displayName. ':</label>';
    $html .= '<input class="text" size="'. $this->size .'" name="'. $this->columnName .'" value="'. $this->defaultValue .'"  />';
    return $html;
    }
    
    
    //validate
    // validates the object with a regular expression
    //
    //Param:  value = the value to match with the regular expression
    //
    //Return: true if string matches the validation rule
    //
    public function validate($value){
        $validated = false;
            if( preg_match($this->validationString, $string)) {
                $validated = true;
            }
        return $validated;
    }
    
    //setvalidation
    // a regular expression to validate the text
    //
    //Param:  string = the value to match with the regular expression
    //
    //Return: none
    //
    public function setValidation($string){
        $this->validationString = $string;
    }
    
}


?>
