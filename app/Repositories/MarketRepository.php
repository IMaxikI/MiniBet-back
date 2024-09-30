<?php

namespace App\Repositories;

use App\Interfaces\IDataBase;
use App\Interfaces\Repositories\IMarketRepository;

class MarketRepository implements IMarketRepository
{
    private IDataBase $db;

    public function __construct(IDataBase $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritDoc
     */
    public function savePreparedData(array $data, int $size): void
    {
        $query = 'INSERT INTO markets (market_id, event_id, market_type, market_type_parameter, scope_type, scope_number) VALUES ';
        $endQuery = ' ON DUPLICATE KEY UPDATE market_type_parameter = VALUES(market_type_parameter)';

        $this->db->writeBatch($query, $data, 'iisdsd', ',(?, ?, ?, ?, ?, ?)', 6, $endQuery);
    }
}