<?php

declare(strict_types=1);

namespace App\Models;

class Outcome
{
    private float $odds;

    private string $outcome_id;

    public function __construct(float $odds, string $outcome_id)
    {
        $this->odds = $odds;
        $this->outcome_id = $outcome_id;
    }

    public function getOdds(): float
    {
        return $this->odds;
    }

    public function getOutcomeId(): string
    {
        return $this->outcome_id;
    }
}