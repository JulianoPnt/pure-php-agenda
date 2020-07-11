<?php

namespace JPF\ExtraValidation;

use PDO;
use JPF\DB\Database;
use Rakit\Validation\Rule;

class UniqueRule extends Rule
{
    protected $message = ":attribute :value is already in use";
    
    protected $fillableParams = ['table', 'column'];
    
    protected $db;
    
    public function __construct()
    {
        $database_obj = new Database();
        $this->db = $database_obj->getConnection(); 
    }
    
    public function check($value): bool
    {
        $this->requireParameters(['table', 'column']);
    
        $column = $this->parameter('column');
        $table = $this->parameter('table');

        $stmt = $this->db->prepare("select count(*) as count from `{$table}` where `{$column}` = :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
	
        // true for valid, false for invalid
        return intval($data['count']) === 0;
    }
}