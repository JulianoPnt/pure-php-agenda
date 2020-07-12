<?php

namespace App\Model;

use PDO;
use JPF\DB\Database;
use JPF\Paginator\Paginator;

class AgendaModel {
    private $database;

    public function __construct()
    {
        $db = new Database();
        $this->database = $db->getConnection();
    }

    public function get() 
    {
        $sql= "SELECT * FROM contacts";
        $stmt = $this->database->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);

        return false;
    }

    public function getPaginated($page, $perPage) 
    {
        $paginator = new Paginator($page,$perPage);

        $sql= "SELECT * FROM contacts LIMIT :offset, :perpage";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':offset', $paginator->offset(), PDO::PARAM_INT); 
        $stmt->bindParam(':perpage', $perPage, PDO::PARAM_INT); 
        $stmt->execute();
        if($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);

        return false;
    }



}