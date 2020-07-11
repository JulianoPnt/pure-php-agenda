<?php

namespace App\Model;

use PDO;
use PDOException;
use JPF\DB\Database;

class AgendaModel {
    private $database;

    public function __construct()
    {
        $db = new Database();
        $this->database = $db->getConnection();
    }

    public function get() 
    {
        $sql= "SELECT email, password FROM contacts";
        $stmt = $this->database->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);

        return false;
    }

    public function getPaginated($page, $perPage) 
    {

    }



}