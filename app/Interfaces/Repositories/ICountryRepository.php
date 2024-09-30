<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\Models\Country;

interface ICountryRepository
{
    /**
     * @param Country[] $countries
     * @return void
     */
    public function saveMany(array $countries): void;
}