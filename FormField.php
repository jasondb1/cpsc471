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
    
    private $columnName;
    private $displayName;
    private $type;
    private $groupName;
    
    private $readOnly= false;
    private $isRequired = false;
    
    //abstract public function toHtml();
    
    public function setRequired(){
        $this->isRequired = true;
    }
    
    public function setReadOnly(){
        $this->readOnly = true;
    }
    
}


?>
