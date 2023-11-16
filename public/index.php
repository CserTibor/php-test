<?php

use App\Controllers\AuthController;
use App\Models\Session;

require('../Connection.php');

require('../Models/Auth.php');
require('../Models/Session.php');
require('../Models/User.php');

require('../Controllers/AuthController.php');

require('../helpers.php');

//$db = Connection::getInstance();
//$db->migrate();
//$db->seed();

function authMiddlewareHandle(): void
{
    $session = Session::get(getBearer());

    if (!$session) {
        jsonResponse(['error' => 'Unauthorized!'], 401);
    }

    if (strtotime($session->getExpiresAt()) < time()) {
        jsonResponse(['error' => 'Unauthorized!'], 401);
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
            http_response_code(404);
            echo json_encode(['error' => 'No route found!']);
            break;
    }
}

handleRequest();
