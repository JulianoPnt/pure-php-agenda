<?php
require __DIR__ . '/vendor/autoload.php';

use JPF\App;
use JPF\Router\Router;
use JPF\Router\Request;
use JPF\Router\Response;
use App\Controller\Home;
use App\Controller\AuthController;

function ValidateToken($bearerToken, $response) {
    $auth = (new AuthController())->IsTokenExpired($bearerToken);
    if($auth) {
        $response->status(401)->toJSON([
            "message" => 'Unauthorized'
        ]);
    } 

    return true;
}

/* Simple route for example
Router::get('/api/example/([0-9]*)', function (Request $req, Response $res) {
    $res->toJSON([
        'test' =>  ['id' => $req->params[0]],
        'status' => 'ok'
    ]);
});

Routes that need authentication:
Add 
ValidateToken($req->getBearerToken(), $res);
*/

Router::get('/', function (Request $req, Response $res) {
    //Vue project redirect
    header('Location: /vue');
});

Router::get('/api', function (Request $req, Response $res) {
    //Just a message to show api is working
    (new Home())->index();
});

//Auth routes
Router::post('/api/auth/login', function (Request $req, Response $res) {
    $login = (new AuthController())->login($req->getJSON());

    if(isset($login['code'])) {
        $res->status($login['code'])->toJSON([
            "message" => $login['message']
        ]);
    } else {
        $res->toJSON($login);
    }    
});

Router::post('/api/auth/register', function (Request $req, Response $res) {
    $register = (new AuthController())->register($req->getJSON());

    if(isset($register['code'])) {
        $res->status($register['code'])->toJSON([
            "message" => $register['message']
        ]);
    } else {
        $res->toJSON($register);
    }    
});


Router::post('/api/auth/checktoken', function (Request $req, Response $res) {
    ValidateToken($req->getBearerToken(), $res);

    $res->toJSON([
        'message' => 'Authorized'
    ]);
});
//End Auth routes

App::run();