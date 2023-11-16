<?php

namespace App\Models;

use Connection;

class User extends Auth
{
    public int $id;
    public string $email;

    private string $passwordHash;

    public function __construct($id, $email, $passwordHash = '')
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public static function findByEmail(string $email): ?User
    {
        $userData = Connection::getInstance()->query(
            "SELECT * FROM users where email = :email",
            ['email' => $email]
        );

        if (empty($userData)) {
            return null;
        }
        $user = $userData[0];

        return new User($user['id'], $user['email'], $user['password']);
    }

    public function authenticate(string $password): bool
    {
        return password_verify($password . Session::SECRET, $this->passwordHash);
    }
}
