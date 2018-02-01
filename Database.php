<?php

class Database {

    private $dbhost;
    private $dbname;
    private $dbuser;
    private $dbpassword;
    private $mysql_link;


    function __construct($host, $name, $user, $password){
        $this->dbhost = $host;
        $this->dbname = $name;
        $this->dbuser = $user;
        $this->dbpassword = $password;
    }
    
    public function opendb(){
        echo"[opendb]";
        $this->mysql_link = new MySQLi($this->dbhost, $this->dbuser, $this->dbpassword, $this->dbname);
		if ($this->mysql_link->connect_errno) {
			die($this->mysql_link->connect_error);
		}
    }

    public function closedb(){
        $this->mysql_link->close();
    }

    public function getdb(){
        return $this;
    }
    
    public function getlink(){
        return $this->mysql_link;
    }

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
    
    //newRecord
    // Edits a record in the database for a specific table
    //
    //Param:    table: the name of the table in the db to edit
    //          valueArray: an associative array of columns to update as [column=>value]
    //          i.e. valueArray = array('description'=>'item1', 'price'=>'42.30');
    //
    //Return: void
    //
    public function newRecord($table, $valueArray){
        
        $this->opendb();
        //convert to sql query
        
    }

    //editRecord
    // Edits a record in the database for a specific table
    //
    //Param:    table: the name of the table in the db to edit
    //          valueArray: an associative array of columns to update as [column=>value]
    //          i.e. valueArray = array('description'=>'item1', 'price'=>'42.30');
    //
    //Return: void
    //

    public function editRecord($table, $valueArray){
        //convert to sql query
    }
    
    //getList
    // Edits a record in the database for a specific table
    //
    //Param:    table: the name of the table in the db to edit
    //          valueArray: an associativ array of columns to update as [column=>value]
    //          i.e. valueArray = array('description'=>'item1', 'price'=>'42.30');
    //
    //Return: void
    //
    
        //newRecord
    // Edits a record in the database for a specific table
    //
    //Param:    table: the name of the table in the db to edit
    //          valueArray: an associative array of columns to update as [column=>value]
    //          i.e. valueArray = array('description'=>'item1', 'price'=>'42.30');
    //
    //Return: void
    //
    public function query($sql){
        echo $sql;
        $this->opendb();
        $retval = $this->mysql_link->query($sql) or die($this->mysql_link->error);
        return $retval;
    }

    public function getList($table, $column, $criteria){
        //convert to sql query
    }

    //TODO: function to get and store rows of data, need ?

}


?>
