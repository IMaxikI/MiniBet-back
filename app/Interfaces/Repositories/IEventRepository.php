<?php

namespace App\Interfaces\Repositories;

interface IEventRepository
{
    /**
     * @param array $data
     * @param int $size
     * @return void
     */
    public function savePreparedData(array $data, int $size): void;
}