<?php 

namespace App\Controller;

use Exception;
use JPF\DB\Database;

class Home
{
    public function index()
    { 
        return ['Welcome' => "Please select a valid route."];   
    }
}