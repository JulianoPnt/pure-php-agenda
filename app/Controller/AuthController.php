<?php

namespace App\Controller;

use JPF\Config\Config;
use \Firebase\JWT\JWT;
use Carbon\Carbon;
use App\Model\AuthModel;
use Rakit\Validation\Validator;
use JPF\ExtraValidation\UniqueRule;

class AuthController
{
    private $jwt_secret_key;
    private $model;

    public function __construct() {
        $this->jwt_secret_key = Config::get('JWT_SECRET');
        $this->model = new AuthModel();
    }

    public function register($data)
    { 
        $validator = new Validator;
        $validator->addValidator('unique', new UniqueRule());

        $validation = $validator->make((array) $data, [
            'first_name'            => 'required',
            'last_name'             => 'required',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:6',
            'confirm_password'      => 'required|same:password',
        ]);
        
        $validation->validate();
        
        if ($validation->fails()) {
            // handling errors
            $errors = $validation->errors();
            return ['code' => 400, 'message' =>  $errors->firstOfAll()];
        } 

        $pass = password_hash($data->password, PASSWORD_DEFAULT);
        unset($data->password);

        $execution = $this->model->register($data, $pass);

        if($execution !== FALSE)
            return ['message' => 'Success'];
        else 
            return ['code' => 500, 'message' => 'Failed to register'];

    }

    public function login($data)
    { 
        $validator = new Validator;

        $validation = $validator->make((array) $data, [
            'email'                 => 'required|email',
            'password'              => 'required|min:6',
        ]);
        
        $validation->validate();
        
        if ($validation->fails()) {
            // handling errors
            $errors = $validation->errors();
            return ['code' => 400, 'message' =>  $errors->firstOfAll()];
        } 
        
        $user_data = $this->model->selectUser($data->email);

        if(password_verify($data->password, $user_data['password'])) {
            unset($data->password);

            $expiration = Carbon::now('America/Sao_Paulo')->addDays(3);

            $payload = array(
                "user_id" => $user_data['id'],
                "user_email" => $user_data['email'],
                "password" => $user_data['password'],
                "logged_at" => Carbon::now('America/Sao_Paulo'),
                "expires_at" => $expiration
            );
            $jwt = JWT::encode($payload, $this->jwt_secret_key);

            $_SESSION['logged'] = 1;
            $_SESSION['user'] = $data->email;

            return ['bearer_token' => $jwt, 'message' => 'Authorized', 'expires_at' => $expiration];
        }

        return ['code' => 401, 'message' => 'Unauthorized'];
    }

    public function IsTokenExpired($bearer) 
    {
        $decoded = JWT::decode($bearer, $this->jwt_secret_key, array('HS256'));
        
        if(Carbon::parse($decoded->expires_at)->gt(Carbon::now('America/Sao_Paulo')));
            return false;
        
        return true;
    }

}