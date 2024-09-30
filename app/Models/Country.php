<?php

declare(strict_types=1);

namespace App\Models;

class Country
{
    private string $country_id;

    private string $country_name;

    private int $o;

    public function __construct(string $country_id, string $country_name, int $o)
    {
        $this->country_id = $country_id;
        $this->country_name = $country_name;
        $this->o = $o;
    }

    public function getCountryId(): string
    {
        return $this->country_id;
    }

    public function getCountryName(): string
    {
        return $this->country_name;
    }

    public function getO(): int
    {
        return $this->o;
    }
}