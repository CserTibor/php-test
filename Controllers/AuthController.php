<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Session;
use App\Views\JsonResponse;

class AuthController
{

    public function login()
    {
        if (!isset($_POST['email'], $_POST['password']) && $_POST['password'] === null && $_POST['email'] === null) {
            JsonResponse::response(['error' => 'Invalid data!'], 401);
        }

        $user = User::findByEmail($_POST['email']);
        if (!$user) {
            JsonResponse::response(['error' => 'Unauthorized!'], 401);
        }

        if (!$user->authenticate($_POST["password"])) {
            JsonResponse::response(['error' => 'Unauthorized!'], 401);
        }
        $session = Session::init($user->id);

        JsonResponse::response(['sessionId' => $session->getToken()]);
    }

    public function me()
    {
        JsonResponse::response([User::getLoggedInUser()]);
    }
}