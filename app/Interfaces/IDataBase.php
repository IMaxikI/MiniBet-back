<?php

namespace App\Interfaces;

use mysqli_result;

interface IDataBase
{
    public function write(string $query, array $params, string $types): bool;

    public function writeBatch(
        string $query, array $data, string $type,
        string $placeholderPattern, int $countColumns,
        string $endQuery = '', int $sizeBatch = 1000
    ): bool;

    public function read(string $query): bool|mysqli_result;
}