<?php

namespace App\Repositories;

use App\Interfaces\IDataBase;
use App\Interfaces\Repositories\IEventRepository;

class EventRepository implements IEventRepository
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
        $query = "INSERT IGNORE INTO events (event_id, tournament_id, event_name, event_start_time) VALUES ";

        $this->db->writeBatch($query, $data, 'iiss', ',(?, ?, ?, ?)', 4);
    }
}