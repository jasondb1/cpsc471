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
ini_set('display_errors',1);
error_reporting(E_ALL);

class Database {

    private $DEBUG = true;
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
// Return:  none
// 

    public function deleteRecord($table, $filter){
        //$this->opendb();
            
           $this->mysql_link->query("DELETE FROM $db_table WHERE uid='$delete_record'");  
         //write log
         //   $details="jn:$jobnumber,$date,$delete_record";
            // write_log_file ($user,'Job Delete',$employee,$details);
        $html= '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
        $html= "<br><br><b><big>Successfully Deleted</b></big><br><br>";
        $html= 	'<input type="Button" value="Back" onclick="location.href=\''.$_SERVER['PHP_SELF']  .'?j='.$masked_jobnumber  .'\'">';
    
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
        if ($this->DEBUG) {echo "SQL: $sql";}
        
        $this->opendb();
        
        $retval = $this->mysql_link->query($sql) or die($this->mysql_link->error);
        return $retval;
    }

////////////////////////////////////////////////////////////////////////////
//
// getList //TODO:
//
// Performs a query either with methodes within the object, or externally
//
// Param:   $table - the table to retrieve values
//          $column - the column of values to return
//          $filter - the criteria for selecting records in the 'WHERE' statement
//
// Return: an array of values retrieved from table
//
    public function getList($table, $column, $filter){
        //convert to sql query
    }

}


?>
