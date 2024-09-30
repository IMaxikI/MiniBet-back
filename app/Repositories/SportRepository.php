<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\IDataBase;
use App\Interfaces\Repositories\ISportRepository;

class SportRepository implements ISportRepository
{
    private IDataBase $db;

    public function __construct(IDataBase $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritDoc
     */
    public function saveMany(array $sports): void
    {
        $query = "INSERT IGNORE INTO sports (sport_id, sport_name, o) VALUES ";
        $data = [];

        foreach ($sports as $sport) {
            $data[] = $sport->getSportId();
            $data[] = $sport->getSportName();
            $data[] = $sport->getO();
        }

        $this->db->writeBatch($query, $data, 'ssi', ',(?, ?, ?)', 3);
    }
}