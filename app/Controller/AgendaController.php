<?php

namespace App\Controller;

use JPF\Config\Config;
use App\Model\AgendaModel;
use \Firebase\JWT\JWT;
use Carbon\Carbon;
use Exception;
use Rakit\Validation\Validator;


class AgendaController
{
    private $jwt_secret_key;
    private $model;

    public function __construct()
    {
        $this->model = new AgendaModel();
        $this->jwt_secret_key = Config::get('JWT_SECRET');
    }

    private function getBearerTokenData($bearer) 
    {
        $decoded = JWT::decode($bearer, $this->jwt_secret_key, array('HS256'));
        return $decoded;
    }

    public function getUserContacts($data, $token) 
    {
        //Use token to get only data from this user
        $info = $this->getBearerTokenData($token);

        //Pagination set by json in body
        if(isset($data->page) && isset($data->perpage)){
            $execution = $this->model->getPaginatedByUser($data->page, $data->perpage, $info->user_id);
            $execution['pagination'] = ['page' => $data->page, 'perpage' => $data->perpage];
        } else {
            $execution = $this->model->getAllByUser($info->user_id);
        } 

        if($execution !== FALSE)
            return ['message' => 'Success', 'data' => $execution];
        else 
            return ['code' => 500, 'message' => 'Failed to find user contacts'];
    }

    public function getUserContactsByID($id, $token) 
    {
        //Use token to get only data from this user
        $info = $this->getBearerTokenData($token);

        //Token to validate if user has access to this contact
        $execution = $this->model->getContactsByID($id, $info->user_id);
        if($execution !== FALSE)
            return ['message' => 'Success', 'data' => $execution];
        else 
            return ['code' => 500, 'message' => 'Failed to find user contact by id'];
    }

    public function insert($data, $token) 
    {
        //Use token to get only data from this user
        $info = $this->getBearerTokenData($token);

        $validator = new Validator;

        $validation = $validator->make((array) $data, [
            'first_name'            => 'required|min:5|max:40',
            'last_name'             => 'alpha_spaces',
            'email'                 => 'required|email|min:8|max:60',
            'address_city'          => 'alpha_spaces|min:5|max:40',
            'address_state'         => 'alpha_spaces|min:5|max:40',
            'address'               => 'alpha_spaces|min:5|max:40',
            'address_number'        => 'integer|min:5',
            'address_cep'           => 'alpha_dash|min:5|max:20',
            'address_district'      => 'alpha_spaces|min:5|max:40',
            'phones'                => 'array',
            'phones.*'              => 'required'
        ]);
        
        $validation->validate();
        
        if ($validation->fails()) {
            // handling errors
            $errors = $validation->errors();
            return ['code' => 400, 'message' =>  $errors->firstOfAll()];
        } 

        $execution_contact = $this->model->insertUserContact($data, $info->user_id);

        if($execution_contact !== FALSE) {
            foreach($data->phones as $phone) {
                $execution_phone = $this->model->insertContactPhone($phone->number, $execution_contact);

                if($execution_phone === FALSE)
                    return ['code' => 500, 'message' => 'Failed to register phone'];
            } 
            return ['message' => 'Success'];
        }
        else 
            return ['code' => 500, 'message' => 'Failed to register'];


    }

    public function update($data, $token) 
    {
        //Use token to verify if this contact belongs to the user
        $info = $this->getBearerTokenData($token);
                
        $validator = new Validator;

        $validation = $validator->make((array) $data, [
            'id'                    => 'required',
            'first_name'            => 'min:5|max:40',
            'last_name'             => 'alpha_spaces',
            'email'                 => 'email|min:8|max:60',
            'address_city'          => 'alpha_spaces|min:5|max:40',
            'address_state'         => 'alpha_spaces|min:5|max:40',
            'address'               => 'alpha_spaces|min:5|max:40',
            'address_number'        => 'integer|min:5|max:80',
            'address_cep'           => 'alpha|min:5|max:20',
            'address_district'      => 'alpha_spaces|min:5|max:40',
        ]);
        
        $validation->validate();
        
        if ($validation->fails()) {
            // handling errors
            $errors = $validation->errors();
            return ['code' => 400, 'message' =>  $errors->firstOfAll()];
        } 

        $execution_contact = $this->model->updateUserContact($data, $info->user_id);

        if($execution_contact !== FALSE) {
            foreach($data->phones as $phone) {
                $execution_phone = $this->model->updateContactPhone($phone->id, $phone->number);

                if($execution_phone === FALSE)
                    return ['code' => 500, 'message' => 'Failed to update phone'];
            } 
            return ['message' => $execution_contact];
        } 
        else 
            return ['code' => 500, 'message' => 'Failed to update'];
    }

    public function delete($id, $token) 
    {
        //Use token to verify if this contact belongs to the user
        $info = $this->getBearerTokenData($token);

        $execution = $this->model->deleteContact($id, $info->user_id);

        if($execution !== FALSE)
            return ['message' => 'Success'];
        else 
            return ['code' => 500, 'message' => 'Failed to find user contacts'];
    }
}