<?php
/*
 * FormHtml.php
 * 
 * Copyright 2018
 * 
 * This class contains functions for database operations
 * 
 */
 
//Used for testing, comment out for production
//ini_set('display_errors',1);
//error_reporting(E_ALL);

class Database {

    private $DEBUG = false;
    private $dbhost;
    private $dbname;
    private $dbuser;
    private $dbpassword;
    private $mysql_link;


////////////////////////////////////////////////////////////////////////////
//
// __construct
//
// Constructor function
//
// Param:   $host - host of the database
//          $name - name of the database
//          $user - username for the database
//          $password - password for database
//
// Return: none
//    
    
    function __construct($host, $name, $user, $password){
        $this->dbhost = $host;
        $this->dbname = $name;
        $this->dbuser = $user;
        $this->dbpassword = $password;
    }
    
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
    
    public function opendb(){
        if ($this->DEBUG) {echo"[opendb]";}
        
        $this->mysql_link = new MySQLi($this->dbhost, $this->dbuser, $this->dbpassword, $this->dbname);
		
        if ($this->mysql_link->connect_errno) {
			die($this->mysql_link->connect_error);
		}
    }

////////////////////////////////////////////////////////////////////////////
//
// closedb
//
// Closes the database
//
// Param:   none
//
// Return:  none
// 

    public function closedb(){
        $this->mysql_link->close();
    }

////////////////////////////////////////////////////////////////////////////
//
// getdb
//
// Returns the database object if required for manual external operations
//
// Param:   none
//
// Return:  database object
// 
    public function getdb(){
        return $this;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// getlink
//
// Returns the mysql_link of the database object if required for manual external operations
//
// Param:   none
//
// Return:  mysql_link
// 

    public function getlink(){
        return $this->mysql_link;
    }

////////////////////////////////////////////////////////////////////////////
//
// deleteRecord //TODO:
//
// Deletes a record from the table matching the filter criteria
//
// Param:   $table - the table to perform a delete
//          $filter - criteria used by the "WHERE" keyword in sql query
//
// Return:  html code stating record was deleted
// 

    public function deleteRecord($table, $filter){
        $this->opendb();
            
        $this->query("DELETE FROM $table WHERE $filter");  

        $html   = '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
        $html   .= "<br><br><b><big>Successfully Deleted</b></big><br><br>";
        $html   .= '<input type="Button" value="Back" onclick="location.href=\''.$_SERVER['PHP_SELF']  .'?j='.$masked_jobnumber  .'\'">';
    
        return $html;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// newRecord
//
// Enters a new record in the database for a specific table
//
// Param:   $table - The name of the table in the db to edit
//          $valueArray -  An associative array of columns to update as [column=>value]
//                         i.e. valueArray = array('description'=>'item1', 'price'=>'42.30');
//
// Return: void
//
    
    public function newRecord($table, $valueArray){
        
        $this->opendb();
        
        //insert into statement INSERT INTO $table (column1 ...) VALUES (val1 ...);
        $sql = "INSERT INTO $table(";
        $count = count($valueArray);
        
        //loop through each field and add column and data to sql query
        foreach ($valueArray as $field=>$value){
            $sql .= '`'. $field   .'`';
            if ($count > 1){
                $sql .= ', ';
            }
            $count--;
        }
        $sql .= ') VALUES (';
        $count = count($valueArray);
        foreach ($valueArray as $field=>$value){
            if ($valueArray[$field] == ''){
                $sql .= 'null';
            }
            else {
                $sql .= '\''. $valueArray[$field]   .'\'';
            }   
            if ($count > 1){
                $sql .= ', ';
            }
            $count--;
        }
        $sql .= ');';
        
        $this->query($sql);
        
        return true;
        
    }

////////////////////////////////////////////////////////////////////////////
//
// updateRecord
//
// Updates a record that already exists in a table
//
// Param:   $table - The name of the table in the db to edit
//          $valueArray -  An associative array of columns to update as [column=>value]
//                         i.e. valueArray = array('description'=>'item1', 'price'=>'42.30');
//
// Return: void
//

    public function updateRecord($table, $valueArray, $filter){
        		
        //Compose an sql update statement
        $sql = "UPDATE $table SET "; 
		
        $count = count($valueArray);
        
        //loop through each field and add column and data to sql query
        foreach ($valueArray as $field=>$value){
            if ($valueArray[$field] == ''){
                $sql .= '`'. $field . '` =  null';
            }
            else {
                $sql .= '`'. $field . '` =  \''. $valueArray[$field]   .'\'';
            }   
            if ($count > 1){
                $sql .= ', ';
            }
            $count--;
        }
        $sql .= ' WHERE '. $filter;
        $sql .= ';';

        //execute sql query
        $this->query($sql);

        return true;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// query
//
// Performs a query either with methodes within the object, or externally
//
// Param:   $sql - The sql query to perform
//
// Return: void
//

    public function query($sql){
        //if ($this->DEBUG) {echo "SQL: $sql";}
        
        $this->opendb();
        
        $retval = $this->mysql_link->query($sql) or die($this->mysql_link->error);
        return $retval;
    }
    
////////////////////////////////////////////////////////////////////////////
//
// getPrimaryKey
//
// Performs a query either with methodes within the object, or externally
//
// Param:   $table - The primary key of the table
//
// Return: the column of the primary key
//

    public function getPrimaryKey($table){
    
        $sql = 'SELECT `COLUMN_NAME` FROM `information_schema`.`COLUMNS` 
        WHERE (`TABLE_SCHEMA` = \''. $this->dbname .'\')  
        AND (`TABLE_NAME` = \''. $table  .'\')  AND (`COLUMN_KEY` = \'PRI\')';
        
        $retval = $this->query($sql);

        $temp = $retval->fetch_row();

        return $temp[0];
    }

////////////////////////////////////////////////////////////////////////////
//
// getList
//
// Performs a query either with methods within the object, or externally
//
// Param:   $table - the table to retrieve values
//          $column - the column of values to return
//          $filter - the criteria for selecting records in the 'WHERE' statement
//          $sort - boolean true or false
//          $sql - a custom sql query
//
// Return: an array of values retrieved from table
//
    public function getList($table, $column, $filter = "1", $sort = true, $sql = ""){
        
        if ($sql == ""){
            $sql = "SELECT $column FROM $table WHERE $filter";
        }
		$retval = $this->query($sql);
		
		while ($row = $retval->fetch_array(MYSQLI_ASSOC)) {
			$list[]=$row[$column];
		}
        if ($sort){
            sort ($list);
        }
        
		return $list;
        
    }
    
////////////////////////////////////////////////////////////////////////////
//
// getListAssoc
//
// Performs a query either with methods within the object, or externally
//
// Param:   $table - the table to retrieve values
//          $column - the column of values to return
//          $filter - the criteria for selecting records in the 'WHERE' statement
//
// Return: an array of values retrieved from table
//
    public function getListAssoc($table, $valueColumn, $keyColumn, $filter = "1", $sort = true, $sql = ""){
        
        if ($sql == ""){
            $sql = "SELECT $valueColumn, $keyColumn FROM $table WHERE $filter";
        }
		$retval = $this->query($sql);
		$list = array();
        
		while ($row = $retval->fetch_array(MYSQLI_ASSOC)) {
            //$list[ $row[$keyColumn]] = $row[$valueColumn];
            $list[ $row[$valueColumn]] = $row[$keyColumn];
		}
        if ($sort){
            krsort ($list);
        }
        
		return $list;
        
    }

}


?>
