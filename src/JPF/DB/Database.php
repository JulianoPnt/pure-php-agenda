<?php 

namespace JPF\DB;

use PDO, PDOException;

// Conexão
define('HOST', 'localhost');
define('DBNAME', 'teste_cohros');  
define('CHARSET', 'utf8');  
define('USER', 'root');  
define('PASSWORD', '');

class Database 
{
    private static $pdo;

    function getConnection() {
        if (!isset(self::$pdo)) {  
            try {  
                $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8', PDO::ATTR_PERSISTENT => TRUE);  
                self::$pdo = new PDO("mysql:host=" . HOST . "; dbname=" . DBNAME . "; charset=" . CHARSET . ";", USER, PASSWORD, $options);  
            } catch (PDOException $e) {  
                print "Error: " . $e->getMessage();  
            }  
        }  
        return self::$pdo; 
    }

}
