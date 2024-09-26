<?php

include $_SERVER['DOCUMENT_ROOT']."/api/_libs/autoload.php";

class add_peers extends RestAPI{
    public function __construct(){
        $this->db = Database::DbConnection();
        $this->dbs = Database::DbConnection();
        print_r($this->db);
        print_r($this->dba);
    }

    public function vali_field(){
        
    }
}

$a = new add_peers();