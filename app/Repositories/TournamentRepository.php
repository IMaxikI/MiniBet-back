<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\IDataBase;
use App\Interfaces\Repositories\ITournamentRepository;

class TournamentRepository implements ITournamentRepository
{
    private IDataBase $db;

    public function __construct(IDataBase $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritDoc
     */
    public function saveMany(array $tournaments): void
    {
        $query = "INSERT IGNORE INTO tournaments (tournament_id, tournament_name, sport_id, country_id) VALUES ";
        $data = [];

        foreach ($tournaments as $tournament) {
            $data[] = $tournament->getTournamentId();
            $data[] = $tournament->getTournamentName();
            $data[] = $tournament->getSport()->getSportId();
            $data[] = $tournament->getCountry()->getCountryId();
        }

        $this->db->writeBatch($query, $data, 'isss', ',(?, ?, ?, ?)', 4);
    }
}