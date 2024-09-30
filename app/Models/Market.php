<?php

declare(strict_types=1);

namespace App\Models;

class Market
{
    private string $market_id;

    private string $market_type;

    private float $market_type_parameter;

    /**
     * @var Outcome[]
     */
    private array $outcomes;

    /**
     * @param string $market_id
     * @param string $market_type
     * @param float $market_type_parameter
     * @param Outcome[] $outcomes
     */
    public function __construct(string $market_id,string $market_type, float $market_type_parameter, array $outcomes)
    {
        $this->market_id = $market_id;
        $this->market_type = $market_type;
        $this->market_type_parameter = $market_type_parameter;
        $this->outcomes = $outcomes;
    }

    public function getMarketType(): string
    {
        return $this->market_type;
    }

    public function getMarketTypeParameter(): float
    {
        return $this->market_type_parameter;
    }

    /**
     * @return Outcome[]
     */
    public function getOutcomes(): array
    {
        return $this->outcomes;
    }

    public function getMarketId(): string
    {
        return $this->market_id;
    }
}