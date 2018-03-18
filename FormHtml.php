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
    private $groups;        //array of group names
    private $itemFields;    //for items
    private $editItems;
    private $fields;        //for regular fields
    private $data;
    private $database;
    private $successPage;
    private $hasItems = false;

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
    
    public function htmlForm($edit = false){
        $html = '<form method="post" action="' .$_SERVER['PHP_SELF'] .'" name="'. $this->formName . '">';
        $html .= '<input class="submit" type="submit" name="submit" value="Submit"/>&nbsp;';
		$html .= '<input class="submit" type="submit" name="submit" value="Cancel" onclick="document.'. $this->formName . '.action=' .$_SERVER['PHP_SELF'] .';"/>&nbsp;';
        $html .= '<br>';
        foreach ($this->groups as $name=>$fields){
            $html .= '<fieldset>';
            $html .= '<legend>'. $name .'</legend>';
            $html .= $this->fieldsToHtml($fields);
            $html .= '</fieldset>';
        }

        if ($this->hasItems){
            $html .= $this->htmlItemFields();
        }
        
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
// fieldsToHtml
//
// Outputs The html code for the fields in the form
//
// Param: $fieldList - a list of all field objects in the form
//
// Return: html code for all of the fields in the form
//
    
    public function fieldsToHtml($fieldList){
        $html = "";
        foreach($fieldList as $field){
            $html .= $field->toHtml();
            //$html .= "\n";
        }
        return $html;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// htmlItemFields
//
// Outputs The html code for the fields in the form, including the script to 
// create additional items
//
// Param: $iFieldList - a list of all field objects in the form
//
// Return: html code for all of the item fields in the form
//    
    
    
//TODO: modify this function to do edit better
//maybe make $iFieldList/$itemFields to array(array(field1, field2, field3), array(field1, field2, field3))
//figure out a good way to pass in sql queries or default values

    public function htmlItemFields($iFieldList = "", $getDefaults = false){
        
        if ($iFieldList == ""){
            $iFieldList = $this->itemFields;
        }
        
        
        if ($getDefaults == true){
            
            //$var_count = count($iFieldList);
            //get number of items
        }
        
        $i=0;
        $var_count = count($iFieldList[0]->getColumnName());
        if ($var_count<1){$var_count=1;}    //make sure at least one item shows up on new po
            
            
            
            while($i<$var_count)
            {
                $htmlItemFields =  '<b>Item '. ($i + 1)  .'</b><br>';
                $htmlItemFields .= '<div id="form_data">';
//TODO:default value  git
                $htmlItemFields .= $this->fieldsToHtml($iFieldList);
                $htmlItemFields .= '<div style="clear:both;"></div>';
                $htmlItemFields .= '</div>';
                
                // //add delete if >1 items
                //if ($i>0 || $var_count>1){
                if ($this->editItems !=""){//This was a bit of hack to allow this
                    //echo '<a style="float:right;" href="'. $_SERVER['PHP_SELF']  .'?remove_item='.$item_id[$i] . '&id='. $id  .'">Remove<img src="images/delete.png" /></a>';
                    $htmlItemFields .= '<a style="float:right;" href="'. $_SERVER['PHP_SELF']  .'?remove_item='.$item_id[$i] . '&id='. $id  .'&refer_page='.$refer_page.'"><img src="images/remove.png" />Remove Item</a>';
                    }
               $htmlItemFields .= '<br><hr>';
                $i++;
            }//end while

        //<!-- Dynamic JS -->
        $htmlScript = "<script>\n";
        $htmlScript .= 'var counter = ' . $var_count . ";\n";
        $htmlScript .= "var limit = 20;\n";
        
        $htmlScript .= "var formhtml ='";
        //$htmlScript .= $htmlItemFields;
        $htmlScript .= $this->fieldsToHtml($iFieldList);
        $htmlScript .= "';\n";

        $htmlScript .= "formhtml += '<div style=\"clear:both;\"></div>';\n";

        $htmlScript .= "function addInput(divName){\n";
        
        $htmlScript .= "             if (counter == limit)  {\n";
        $htmlScript .= '                  alert("You have reached the limit of adding " + counter + " items");'. "\n";
        $htmlScript .= "             }\n";
        $htmlScript .= "             else {"; 
                  
        $htmlScript .= "            var div1 = document.createElement('div'); \n"; 
                  
                    //Get template data  
        $htmlScript .= "            div1.innerHTML = '<b>Item ' + (counter + 1) + '</b><br>';\n";
        $htmlScript .= "            div1.innerHTML += formhtml;\n";
        $htmlScript .= "            div1.innerHTML += '<br><hr>';\n";		
                  
                    //append to our form, so that template data  
                    // //become part of form  
        $htmlScript .= "            document.getElementById(divName).appendChild(div1);\n";  
        $htmlScript .= "            counter++;\n";
        $htmlScript .= "            }\n";
        $htmlScript .= "    }\n";
        $htmlScript .= "</script>\n";



        //create items
        $htmlItems = "";

        $htmlItems .= "<fieldset>\n";
        $htmlItems .=    "<legend>Items</legend>\n";
        $htmlItems .=       "<div style=\"float:clear;\"></div>\n";
        $htmlItems .=       "<div id=\"dynamicInput\">\n";
                    
        $htmlItems .= $htmlItemFields;

        $htmlItems .=  "</div>\n";
        $htmlItems .=  "<span id=\"writeroot\"></span>\n";
        $htmlItems .=   '<input class="submit" type="button" value="Add Another Item" onClick="addInput(\'dynamicInput\');"><br>' . "\n";
        $htmlItems .=    '<input class="submit" value="Submit" type="submit" name="submit">' . "\n";
                    
        $htmlItems .= $htmlScript;

        $htmlItems .= '<div style="float:clear;"></div><br>' ."\n";
        $htmlItems .= '</fieldset>' . "\n";
        
        return $htmlItems;
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
// setGroups
//
// sets the group objects contained within the form
//
// Param: $groups - an array of field objects for the form
//
// Return: none
//

    public function setGroups($groups){
        $this->groups = $groups;
        $allFields = array();
        
        //enter them into fields for this array for other functions
        foreach ($this->groups as $name=>$fields){
            $allFields = array_merge($allFields, $fields);
        }
                
    $this->setfields($allFields);

    }
    
////////////////////////////////////////////////////////////////////////////
//
// setItemFields
//
// sets the field objects contained within the form
//
// Param: $fields - an array of field objects for the form
//
// Return: none
//

    public function setItemFields($fields){
        $this->itemFields = $fields;
        $this->hasItems = true;
        
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
// setEditCount
//
// Sets the title for the form
//
// Param: $name - the title
//
// Return: none
//

    public function setEditCount($count){
        $this->editItems = $count;
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
        
        $sql = "SELECT * FROM $table WHERE $filter";
        $retval = $database->query($sql);

        //return should be only a single row of values
        $row = $retval->fetch_assoc();
        

        foreach($this->fields as $field){
            $colName = $field->getColumnName();
            //echo $colName;
            $field->setDefaultValue($row[$colName]);
        }
//var_dump($this->fields);
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
    $html .= '<input type="Button" value="Back" onclick="location.href=\''. $this->successPage  .'\'">'. "\n";
    
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
		$html .= '<input type="Button" value="Back" onclick="history.go(-1)">'. "\n";
    
    return $html;
   }
}
