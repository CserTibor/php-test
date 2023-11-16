<?php

use App\Models\Session;

class Connection
{
    private const DB_HOST = 'neosoft-db:3306';
    private const DB_DATABASE = 'neosoft-db';
    private const DB_USERNAME = 'neosoft';
    private const DB_PASSWORD = 'neosoft';

    private static ?Connection $instance = null;

    private PDO $connection;

    public function __construct()
    {
        $connection = new PDO(
            'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_DATABASE,
            self::DB_USERNAME,
            self::DB_PASSWORD,
        );

        $this->connection = $connection;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query(string $query, array $params = []): array
    {
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $preparedQuery = $this->connection->prepare($query);

        foreach ($params as $key => $value) {
            $preparedQuery->bindParam($key, $value);
        }

        $preparedQuery->execute();
        $preparedQuery->setFetchMode(PDO::FETCH_ASSOC);

        return $preparedQuery->fetchAll();
    }


    public function execute(string $query): void
    {
        $this->connection->exec($query);
    }

    public function migrate(): void
    {
        $migrations = [
            'users' => 'CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) UNIQUE NOT NULL,
                password TEXT NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP()
            );',
            'sessions' => 'CREATE TABLE sessions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                token VARCHAR(255) UNIQUE NOT NULL,
                expires_at TIMESTAMP NOT NULL DEFAULT (DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 HOUR)),
                FOREIGN KEY (user_id) REFERENCES users(id)
            );'
        ];

        foreach ($migrations as $table => $migration) {
            $query = "SELECT EXISTS (
                        SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '" . $table . "'
                    ) as table_exists";

            $result = $this->query($query);

            if ($result[0]['table_exists'] === '0') {
                $this->execute($migration);
            }
        }
    }

    public function seed(): void
    {
        $query = "SELECT COUNT(*) as user_count FROM users WHERE email = 'teszt@teszt.com' ";
        $data = $this->query($query);

        if ((int)$data[0]['user_count'] === 0) {
            $password = password_hash('password' . Session::SECRET, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (email, password) VALUES ('teszt@teszt.com', '" . $password . "')";

            $this->execute($sql);
        }
    }
}