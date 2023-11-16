<?php

namespace App\Models;

use Connection;

class Session
{

    public const SECRET = 'neosoft123!';
    public const EXPIRATION = 3600;
    private int $id;
    private int $userId;
    private string $token;
    private string $expiresAt;

    private static ?Session $instance = null;

    public function __construct($id, $userId, $token, $expiresAt)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->token = $token;
        $this->expiresAt = $expiresAt;
    }

    public static function get(string $token): ?self
    {
        if (self::$instance === null) {
            $db = Connection::getInstance();
            $query = "SELECT * FROM sessions WHERE token = '$token'";
            $data = $db->query($query);

            if (empty($data)) {
                return null;
            }

            $session = new self($data[0]['id'], $data[0]['user_id'], $token, $data[0]['expires_at']);
            self::$instance = $session;
        }

        return self::$instance;
    }

    public static function init(int $userId): Session
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date("Y-m-d H:i:s", time() + self::EXPIRATION);

        $db = Connection::getInstance();
        $sql = "INSERT INTO sessions (user_id, token, expires_at) VALUES ('$userId', '$token', '$expiresAt');";
        $db->execute($sql);

        return self::get($token);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiresAt(): string
    {
        return $this->expiresAt;
    }
}