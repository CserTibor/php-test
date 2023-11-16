<?php

namespace App\Controllers;

use App\Models\Auth;
use App\Models\User;
use App\Models\Session;

class AuthController
{

    public function login(): string
    {
        if (!isset($_POST['email'], $_POST['password']) && $_POST['password'] === null && $_POST['email'] === null) {
            return jsonResponse(['error' => 'Invalid data!'], 401);
        }

        $user = User::findByEmail($_POST['email']);
        if (!$user) {
            return jsonResponse(['error' => 'Unauthorized!'], 401);
        }

        if (!$user->authenticate($_POST["password"])) {
            return jsonResponse(['error' => 'Unauthorized!'], 401);
        }
        $session = Session::init($user->id);

        return jsonResponse(['sessionId' => $session->getToken()]);
    }

    public function me(): string
    {
        return jsonResponse([User::getLoggedInUser()]);
    }
}