<?php
/*
 * FormHtml.php
 * 
 * Copyright 2018
 * 
 * This class displays and produces the form data
 * 
 */
 
ini_set('display_errors',1);
error_reporting(E_ALL);
 
require('FormTextField.php');
require('FormTextBox.php');
require('FormDateField.php');
require('FormCheckbox.php');
require('FormHidden.php');
require('FormSelect.php');

class FormHtml {

    private $title = "title";
    private $formName = "formname";
    private $a_groups; //not currentlyused
    private $fields;
    private $data;
    private $database;
    private $successPage;

    private $color1 = "#000000";
    private $color2 = "#f0f0f0";
    private $color3 = "#e0e0e0";
    
    private $sql = "";
    
////////////////////////////////////////////////////////////////////////////
//
// htmlForm
//
// Outputs The html code for the form
//
// Param:    none
//
// Return: a string of html that contains the form code
//
    
    public function htmlForm(){
        $html = '<form method="post" action="' .$_SERVER['PHP_SELF'] .'" name="'. $this->formName . '">';
        $html .= '<input class="submit" type="submit" name="submit" value="Submit"/>&nbsp;';
		$html .= '<input class="submit" type="submit" name="submit" value="Cancel" onclick="document.'. $this->formName . '.action=' .$_SERVER['PHP_SELF'] .';"/>&nbsp;';
        $html .= '<br>';
        $html .= '<fieldset>';
        $html .= '<legend>'. $this->title .'</legend>';
        $html .= $this->toHtml($this->fields);
        $html .= '</fieldset>';
        $html .= "</form>";
        return $html;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// getData
//
// gets the values for each field in the form
//
// Param:    $post - the $_POST data from a submitted form
//
// Return: an array of ($field=>$value) pairs
//
    
    public function getData($post){
        $values = array();
        foreach($this->fields as $field){
            $colName = $field->columnName;
            $values[$colName] = $post[$colName];
        }
     $this->data = $values;
     return $values;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// toHtml
//
// Outputs The html code for the fields in the form
//
// Param: $fieldList - a list of all field objects in the form
//
// Return: html code for all of the fields in the form
//
    
    private function toHtml($fieldList){
        $html = "";
        foreach($fieldList as $field){
            //var_dump($field);
            $html .= $field->toHtml();
        }
        return $html;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// setFields
//
// sets the field objects contained within the form
//
// Param: $fields - an array of field objects for the form
//
// Return: none
//

    public function setFields($fields){
        $this->fields = $fields;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// setSuccessPage
//
// sets the field objects contained within the form
//
// Param: $page - page to go to after success
//
// Return: none
//

    public function setSuccessPage($page){
        $this->successPage = $page;
    }

////////////////////////////////////////////////////////////////////////////
//
// setTitle
//
// Sets the title for the form
//
// Param: $name - the title
//
// Return: none
//

    public function setTitle($name){
        $this->title = $name;
    }

////////////////////////////////////////////////////////////////////////////
//
// setFormName
//
// Sets the name of the form - may be required if multiple forms are present on one page
//
// Param: $name - the form name
//
// Return: none
//
    
    public function setFormName($name){
        $this->formName = $name;
    }
 
////////////////////////////////////////////////////////////////////////////
//
// setDefaults
//
// Sets the default values from database data
//
// Param: $name - the form name
//
// Return: none
//
    public function setDefaults($database, $table, $filter){
        
        $sql = "SELECT * FROM $db_table_jobfile WHERE jobnumber = $edit_record";
        $retval = $database->query($sql);

        //return should be only a single row of values
        $row = $retval->fetch_assoc();
        
        foreach($this->fields as $field){
            $field->setDefaultValue($row[$field]);
        }
    }
   
////////////////////////////////////////////////////////////////////////////
//
// htmlSuccess
//
// Code for successful return of html
//
// Param: none
//
// Return: html code displayed for success
//

    public function successHtml(){
    $html = '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
    $html .=  "<br><br><b><big>Successfully Submitted</b></big><br><br>";
    $html .= '<input type="Button" value="Back" onclick="location.href=\''. $this->successPage  .'\'">';
    
    return $html;
   }
   
////////////////////////////////////////////////////////////////////////////
//
// htmlFailure
//
// Code to display for failure
//
// Param: none
//
// Return: html code displayed for failure
//

    public function failureHtml(){
		$html =  '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
		$html .= "<br><br><b><big>Hey! You forgot some information!</b></big><br>Fill in all of the fields marked with an *<br><br>";
		$html .= '<input type="Button" value="Back" onclick="history.go(-1)">';
    
    return $html;
   }
}
