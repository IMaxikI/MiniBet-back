<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\Models\Tournament;

interface ITournamentRepository
{
    /**
     * @param Tournament[] $tournaments
     * @return void
     */
    public function saveMany(array $tournaments): void;
}