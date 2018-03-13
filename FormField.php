<?php
/*
 * FormField.php
 * 
 * Copyright 2018
 * 
 * This class displays and produces the form data
 * 
 */

class FormField{
    
    //type can be text,textbox, checkbox,radio, pulldown, date, hidden
    
    public $columnName;
    public $displayName;
    protected $type;
    protected $groupName;
    
    protected $readOnly= false;
    protected $isRequired = false;
    protected $defaultValue;
    
    //abstract public function toHtml();

////////////////////////////////////////////////////////////////////////////
//
// opendb
//
// Opens the database to perform operations
//
// Param:   none
//
// Return:  none
//

    public function setRequired(){
        $this->isRequired = true;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// setReadOnly
//
// Sets field to readonly when displaying
//
// Param:   none
//
// Return:  none
//

    public function setReadOnly(){
        $this->readOnly = true;
    }

////////////////////////////////////////////////////////////////////////////
//
// setDefaultValue
//
// Sets the default value for the field
//
// Param:   $value - the default value to set
//
// Return:  none
//

    public function setDefaultValue($value){
        $this->defaultValue = $value;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// getDefaultValue
//
// Sets the default value for the field
//
// Param:   none
//
// Return:  the default value to set
//

    public function getDefaultValue(){
        return $this->defaultValue;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// getColumnName
//
// Sets the default value for the field
//
// Param:   none
//
// Return:  column name
//

    public function getColumnName(){
        return $this->columnName;
    }
 
////////////////////////////////////////////////////////////////////////////
//
// __toString
//
// magic method to display string for the object
//
// Param:   none
//
// Return:  none
//

    public function __toString(){
        return $this->columnName;
    }
    
}


?>
