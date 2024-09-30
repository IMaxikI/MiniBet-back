<?php

namespace App\Interfaces\Repositories;

use App\Models\Sport;

interface ISportRepository
{
    /**
     * @param Sport[] $sports
     * @return void
     */
    public function saveMany(array $sports): void;
}