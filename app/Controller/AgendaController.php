<?php

namespace App\Controller;

use JPF\Config\Config;
use App\Model\AgendaModel;
use Carbon\Carbon;
use Rakit\Validation\Validator;


class AgendaController
{
    private $model;

    public function __construct()
    {
        $this->model = new AgendaModel();
    }

    public function getAll($data) 
    {
        return [$data];
    }

    public function getByID($id) 
    {
        return [$id];
    }

    public function insert($data) 
    {
        return [$data];
    }

    public function update($data) 
    {
        return [$data];
    }

    public function delete($id) 
    {
        return [$id];
    }
}