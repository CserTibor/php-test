<?php

use App\Controllers\AuthController;
use App\Models\Auth;
use App\Models\Session;
use App\Views\JsonResponse;

require('../Connection.php');

require('../Models/Auth.php');
require('../Models/Session.php');
require('../Models/User.php');

require('../Views/JsonResponse.php');

require('../Controllers/AuthController.php');

require('../helpers.php');

//$db = Connection::getInstance();
//$db->migrate();
//$db->seed();

function authMiddlewareHandle(): void
{
    $session = Session::get(Auth::getBearer());

    if (!$session) {
        JsonResponse::response(['error' => 'Unauthorized!'], 401);
    }

    if (strtotime($session->getExpiresAt()) < time()) {
        JsonResponse::response(['error' => 'Unauthorized!'], 401);
    }
}

function handleRequest(): void
{
    $url = $_SERVER['REQUEST_URI'];
    $urlParts = explode('/', $url);

    $method = $_SERVER['REQUEST_METHOD'];

    $controller = new AuthController();

    switch ($urlParts[1]) {
        case 'login':
            if ($method === 'POST') {
                $controller->login();
            }
            break;
        case 'me':
            if ($method === 'GET') {
                authMiddlewareHandle();
                $controller->me();
            }
            break;
        default:
            JsonResponse::response(['error' => 'No route found!'],404);
            break;
    }
}

handleRequest();
