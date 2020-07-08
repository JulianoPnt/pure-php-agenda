<?php

require_once('../vendor/autoload.php');

use JPF\DB\Database;

$a = new Database();
print_r($a->getConnection());