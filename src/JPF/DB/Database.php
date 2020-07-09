<?php 

namespace JPF\DB;

use PDO, PDOException;
use App\Lib\Config;

// Conexão
define('HOST', Config::get('DB_HOST'));
define('DBNAME', Config::get('DB_NAME'));  
define('CHARSET',  Config::get('DB_CHARSET'));  
define('USER',  Config::get('DB_USER'));  
define('PASSWORD',  Config::get('DB_PASSWORD'));

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
