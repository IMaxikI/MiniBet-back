<?php

namespace App\Repositories;

use App\Interfaces\IDataBase;
use App\Interfaces\Repositories\ICountryRepository;

class CountryRepository implements ICountryRepository
{
    private IDataBase $db;

    public function __construct(IDataBase $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritDoc
     */
    public function saveMany(array $countries): void
    {
        $query = "INSERT IGNORE INTO countries (country_id, country_name, o) VALUES ";
        $data = [];

        foreach ($countries as $country) {
            $data[] = $country->getCountryId();
            $data[] = $country->getCountryName();
            $data[] = $country->getO();
        }

        $this->db->writeBatch($query, $data, 'ssi', ',(?, ?, ?)', 3);
    }
}