<?php

namespace App\Services;

use App\Interfaces\IDataBase;
use Exception;
use mysqli;
use mysqli_result;

class DBService implements IDataBase
{
    private string $host;

    private string $user;

    private string $password;

    private string $database;

    private int $port;

    private mysqli $connection;

    public function __construct(string $host, string $user, string $password, string $database, int $port)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->port = $port;

        $this->connection = $this->connect();
    }

    private function connect(): mysqli
    {
        $connection = new mysqli($this->host, $this->user, $this->password, $this->database, $this->port);

        if ($connection->connect_error) {
            echo 'Ошибка: Невозможно подключиться к MySQL ' . $connection->connect_error;
            exit;
        }

        return $connection;
    }

    public function write(string $query, array $params, string $types): bool
    {
        try {
            $stmt = $this->connection->prepare($query);

            if (!$stmt) {
                throw new Exception("Ошибка подготовки запроса: " . $this->connection->error);
            }

            $stmt->bind_param($types, ...$params);

            if (!$stmt->execute()) {
                throw new Exception("Ошибка выполнения запроса: " . $stmt->error);
            }

            echo substr($query, 0, 30);
            printf("Строк затронуто: %d.\n", $stmt->affected_rows);

            $stmt->close();

            return true;
        } catch (Exception $e) {
            echo $e->getMessage();

            return false;
        }
    }

    public function writeBatch(string $query, array $data, string $type, $placeholderPattern, int $countColumns, string $endQuery = '', int $sizeBatch = 1000): bool
    {
        $chunks = array_chunk($data, $sizeBatch * $countColumns);

        foreach ($chunks as $chunk) {
            $countRepeat = count($chunk) / $countColumns;

            $types = str_repeat($type, $countRepeat);
            $placeholders = str_repeat($placeholderPattern, $countRepeat);
            $placeholders[0] = ' ';

            $queryBath = $query . $placeholders . $endQuery;

            $this->write($queryBath, $chunk, $types);
        }

        return true;
    }

    public function read($query): bool|mysqli_result
    {
        $result = $this->connection->query($query);

        if (!$result) {
            echo "Ошибка: " . $this->connection->error;
        }

        return $result;
    }
}