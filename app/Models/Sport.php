<?php

declare(strict_types=1);

namespace App\Models;

class Sport
{
    private string $sport_id;

    private string $sport_name;

    private int $o;

    public function __construct(string $sport_id, string $sport_name, int $o)
    {
        $this->sport_id = $sport_id;
        $this->sport_name = $sport_name;
        $this->o = $o;
    }

    public function getSportId(): string
    {
        return $this->sport_id;
    }

    public function getSportName(): string
    {
        return $this->sport_name;
    }

    public function getO(): int
    {
        return $this->o;
    }
}