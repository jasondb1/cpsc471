<?php

class Database {

    private $dbhost;
    private $dbname;
    private $dbuser;
    private $dbpassword;


    function __construct($host, $name, $user, $password){
        $this->dbhost = $host;
        $this->dbname = $name;
        $this->dbuser = $user;
        $this->dbpassword = $password;
    }
    
    public function opendb(){
    
    }

    public function closedb(){
        
    }



    public function deleteRecord($table, $key){
        
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
        
        //opendb
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

    public function getList($table, $column, $criteria){
        //convert to sql query
    }

    //TODO: function to get and store rows of data, need ?

}


?>
