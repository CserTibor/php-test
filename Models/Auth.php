<?php

namespace App\Models;


use Connection;

class Auth
{

    private static ?User $instance = null;

    public static function getLoggedInUser(): ?User
    {
        if (self::$instance === null) {
            $token = getBearer();
            $db = Connection::getInstance();
            $query = "SELECT users.* FROM users 
                      LEFT JOIN sessions ON sessions.user_id = users.id 
                      WHERE sessions.token = '$token'";
            $data = $db->query($query);

            if (empty($data)) {
                return null;
            }

            $user = new User($data[0]['id'], $data[0]['email']);
            return self::$instance = $user;
        }

        return self::$instance;
    }
}