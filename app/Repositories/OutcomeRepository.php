<?php

namespace App\Repositories;

use App\Interfaces\IDataBase;
use App\Interfaces\Repositories\IOutcomeRepository;

class OutcomeRepository implements IOutcomeRepository
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
        $query = "INSERT IGNORE INTO outcomes (outcome_id ,market_id, odds) VALUES ";
        $endQuery = ' ON DUPLICATE KEY UPDATE odds = VALUES(odds)';

        $this->db->writeBatch($query, $data, 'iid', ',(?, ?, ?)', 3, $endQuery);
    }
}