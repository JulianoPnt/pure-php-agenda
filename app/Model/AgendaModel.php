<?php

namespace App\Model;

use Exception;
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

    public function getPaginatedByUser($page, $perPage, $user_id) 
    {
        $paginator = new Paginator($page,$perPage);
        $offset = $paginator->offset();
        
        $sql= "SELECT * FROM contacts WHERE user_id = :user LIMIT :offset, :perpage";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':user', $user_id, PDO::PARAM_INT); 
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT); 
        $stmt->bindParam(':perpage', $perPage, PDO::PARAM_INT); 
        $stmt->execute();
        if($stmt->rowCount() > 0)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        return false;
    }

    public function getAllByUser($user_id) 
    {
        $sql= "SELECT * FROM contacts WHERE user_id = :user";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':user', $user_id, PDO::PARAM_INT); 
        $stmt->execute();
        if($stmt->rowCount() > 0)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        return false;
    }

    public function getContactsByID($contact_id, $user_id) 
    {
        $sql= "SELECT * FROM contacts WHERE user_id = :user AND id = :contact_id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':user', $user_id, PDO::PARAM_INT); 
        $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT); 
        $stmt->execute();

        $sql2= "SELECT * FROM contacts_phones WHERE contact_id = :contact_id";
        $stmt2 = $this->database->prepare($sql2);
        $stmt2->bindParam(':contact_id', $contact_id, PDO::PARAM_INT); 
        $stmt2->execute();

        if($stmt->rowCount() > 0)
            return ['contact' => $stmt->fetchAll(PDO::FETCH_ASSOC), 'phones' => $stmt2->fetchAll(PDO::FETCH_ASSOC)];

        return false;
    }

    public function insertUserContact($data, $user_id) 
    {
        $stmt = $this->database->prepare('INSERT INTO contacts (user_id, first_name, last_name, email, address_city, address_state, address, address_number, address_cep, address_district) VALUES (:user_id, :first_name, :last_name, :email, :address_city, :address_state, :address, :address_number, :address_cep, :address_district)');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); 
        $stmt->bindParam(':first_name', $data->first_name, PDO::PARAM_STR); 
        $stmt->bindParam(':last_name', $data->last_name, PDO::PARAM_STR); 
        $stmt->bindParam(':email', $data->email, PDO::PARAM_STR); 
        $stmt->bindParam(':address_city', $data->address_city, PDO::PARAM_STR); 
        $stmt->bindParam(':address_state', $data->address_state, PDO::PARAM_STR); 
        $stmt->bindParam(':address', $data->address, PDO::PARAM_STR); 
        $stmt->bindParam(':address_number', $data->address_number, PDO::PARAM_INT); 
        $stmt->bindParam(':address_cep', $data->address_cep, PDO::PARAM_STR); 
        $stmt->bindParam(':address_district', $data->address_district, PDO::PARAM_STR); 
        $stmt->execute();
        $last_id = $this->database->lastInsertId();

        if($stmt->rowCount() === 0) 
            return false;

        return $last_id; 
    }
    
    public function insertContactPhone($phone, $contact_id) 
    {
        $stmt = $this->database->prepare('INSERT INTO contacts_phones (contact_id, number) VALUES (:contact_id, :number)');
        $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT); 
        $stmt->bindParam(':number', $phone, PDO::PARAM_STR); 
        $stmt->execute();

        if($stmt->rowCount() !== 0) 
            return true;

        return false; 
    }

    public function updateUserContact($data, $user_id) 
    {
        $sql = 'UPDATE contacts SET';
        if(isset($data->first_name)) 
            $sql .= ' first_name = :first_name,';

        if(isset($data->last_name)) 
            $sql .= ' last_name = :last_name,';

        if(isset($data->email)) 
            $sql .= ' email = :email,';        
        
        if(isset($data->address_city)) 
            $sql .= ' address_city = :address_city,';
        
        if(isset($data->address_state)) 
            $sql .= ' address_state = :address_state,';
        
        if(isset($data->address)) 
            $sql .= ' address = :address,';
        
        if(isset($data->address_number)) 
            $sql .= ' address_number = :address_number,';
        
        if(isset($data->address_cep)) 
            $sql .= ' address_cep = :address_cep,';

        if(isset($data->address_district)) 
            $sql .= ' address_district = :address_district,';

        $sql = rtrim($sql, ",");
        $sql .= ' WHERE user_id = :user_id AND id = :contact_id';

        $stmt = $this->database->prepare($sql);
        
        if(isset($data->first_name)) 
            $stmt->bindParam(':first_name', $data->first_name, PDO::PARAM_STR); 

        if(isset($data->last_name)) 
            $stmt->bindParam(':last_name', $data->last_name, PDO::PARAM_STR); 

        if(isset($data->email)) 
            $stmt->bindParam(':email', $data->email, PDO::PARAM_STR); 

        if(isset($data->address_city)) 
            $stmt->bindParam(':address_city', $data->address_city, PDO::PARAM_STR); 

        if(isset($data->address_state)) 
            $stmt->bindParam(':address_state', $data->address_state, PDO::PARAM_STR); 

        if(isset($data->address)) 
            $stmt->bindParam(':address', $data->address, PDO::PARAM_STR); 

        if(isset($data->address_number)) 
            $stmt->bindParam(':address_number', $data->address_number, PDO::PARAM_INT); 

        if(isset($data->address_cep)) 
            $stmt->bindParam(':address_cep', $data->address_cep, PDO::PARAM_STR); 

        if(isset($data->address_district)) 
            $stmt->bindParam(':address_district', $data->address_district, PDO::PARAM_STR); 

        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); 
        $stmt->bindParam(':contact_id', $data->id, PDO::PARAM_INT); 

        $stmt->execute();

        if($stmt->rowCount() !== 0) 
            return true;

        return false; 
    }
    
    public function updateContactPhone($id, $number) 
    {
        $stmt = $this->database->prepare('UPDATE contacts_phones SET number = :number WHERE id = :id');
        $stmt->bindParam(':number', $number, PDO::PARAM_INT); 
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute();

        if($stmt->rowCount() !== 0) 
            return true;

        return false; 
    }

    public function deleteContact($contact_id, $user_id)
    {
        try {
            $this->database->beginTransaction();
    
            $stmt = $this->database->prepare('DELETE FROM contacts WHERE id = :id AND user_id = :user_id');
            $stmt->bindParam(':id', $contact_id, PDO::PARAM_INT); 
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); 
            $stmt->execute();
    
            $stmt2 = $this->database->prepare('DELETE FROM contacts_phones WHERE contact_id = :contact_id');
            $stmt2->bindParam(':contact_id', $contact_id, PDO::PARAM_INT); 
            $stmt2->execute();
    
            if($stmt->rowCount() !== 0 && $stmt2->rowCount() !== 0)  {
                $this->database->commit();
                return true;
            }
        } catch (Exception $e) {
            $this->database->rollback();
            return false; 
        }
    }
}