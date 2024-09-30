<?php

declare(strict_types=1);

namespace App\Models;

class Scope
{
    private string $scope_type;

    private float $scope_number;

    /**
     * @var Market[]
     */
    private array $markets;

    /**
     * @param string $scope_type
     * @param int $scope_number
     * @param Market[] $markets
     */
    public function __construct(string $scope_type, int $scope_number, array $markets)
    {
        $this->scope_type = $scope_type;
        $this->scope_number = $scope_number;
        $this->markets = $markets;
    }

    public function getScopeType(): string
    {
        return $this->scope_type;
    }

    public function getScopeNumber(): float
    {
        return $this->scope_number;
    }

    /**
     * @return Market[]
     */
    public function getMarkets(): array
    {
        return $this->markets;
    }
}