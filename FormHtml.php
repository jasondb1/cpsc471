<?php

/*
 * FormHtml.php
 * 
 * Copyright 2018
 * 
 * This class displays and produces the form data
 * 
 */

class FormHtml {

    private $title = "title";
    private $formName = "formname";
    private $a_groups;
    private $a_fields;
    private $database;

    private $color1 = "#000000";
    private $color2 = "#f0f0f0";
    private $color3 = "#e0e0e0";
    
    private $sql = "";
    
    public function htmlForm($fieldList){
        $html = '<form method="post" action="' .$_SERVER['PHP_SELF'] .' name="'. $this->formName . '">';
        $html .= '<input class="submit" type="submit" name="submit" value="Submit"/>&nbsp;';
		$html .= '<input class="submit" type="submit" name="submit" value="Cancel" onclick="document.'. $this->formName . '.action=' .$_SERVER['PHP_SELF'] .';"/>&nbsp;';
        $html .= '<br>';
        $html .= $this->toHtml($fieldList);
        
        $html .= "</form>";
        return $html;
    }
    
    public function getData($fieldList){
    
    }
    
    public function toHtml($fieldList){
        $html = "";
        foreach($fieldList as $field){
            //var_dump($field);
            $html .= $field->toHtml();
        }
        return $html;
    }
    
}
