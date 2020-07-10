<?php
require __DIR__ . '/vendor/autoload.php';

use App\Lib\App;
use App\Lib\Router;
use App\Lib\Request;
use App\Lib\Response;
use App\Controller\Home;
use App\Controller\AuthController;

function ValidateRequest($bearerToken) {
    return (new AuthController())->IsTokenExpired($bearerToken);
}

/* Simple route for example
Router::get('/api/example/([0-9]*)', function (Request $req, Response $res) {
    $res->toJSON([
        'test' =>  ['id' => $req->params[0]],
        'status' => 'ok'
    ]);
});
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
    $auth = ValidateRequest($req->getBearerToken());
    if($auth) {
        $res->status(401)->toJSON([
            "message" => 'Unauthorized'
        ]);
    } 

    $res->toJSON([
        'message' => 'Authorized'
    ]);
});
//End Auth routes

App::run();