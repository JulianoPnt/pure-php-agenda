<?php

namespace App\Model;

use PDO;
use PDOException;
use JPF\DB\Database;

class AuthModel {
    private $database;

    public function __construct()
    {
        $db = new Database();
        $this->database = $db->getConnection();
    }

    public function selectUser($email) 
    {
        $sql= "SELECT id, email, password FROM users WHERE email = :email";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR); 
        $stmt->execute();
        if($stmt->rowCount() === 1)
            return $stmt->fetch(PDO::FETCH_ASSOC);

        return false;
    }

    public function register($data, $hash_password) {
        $stmt = $this->database->prepare('INSERT INTO users (first_name, last_name, email, password) VALUES(:first_name, :last_name, :email, :password)');
        $stmt->bindParam(':first_name', $data->first_name, PDO::PARAM_STR); 
        $stmt->bindParam(':last_name', $data->last_name, PDO::PARAM_STR); 
        $stmt->bindParam(':email', $data->email, PDO::PARAM_STR); 
        $stmt->bindParam(':password', $hash_password, PDO::PARAM_STR); 
        $stmt->execute();
            
        if($stmt->rowCount() === 1) 
            return true;

        return false;
    }

}