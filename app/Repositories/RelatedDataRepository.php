<?php

namespace App\Repositories;

use App\Interfaces\IDataBase;
use App\Interfaces\Repositories\IRelatedDataRepository;

class RelatedDataRepository implements IRelatedDataRepository
{
    private IDataBase $db;

    public function __construct(IDataBase $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $query = "
            SELECT t.tournament_id,
                   t.tournament_name,
                   s.sport_id,
                   s.sport_name,
                   c.country_id,
                   c.country_name,
                   e.event_id,
                   e.event_name,
                   e.event_start_time,
                   m.market_id,
                   m.market_type,
                   m.market_type_parameter,
                   m.scope_type,
                   m.scope_number,
                   o.outcome_id,
                   o.odds
            FROM tournaments t
                JOIN sports s ON t.sport_id = s.sport_id
                JOIN countries c ON t.country_id = c.country_id
                JOIN events e ON t.tournament_id = e.tournament_id
                JOIN markets m ON e.event_id = m.event_id
                JOIN outcomes o ON m.market_id = o.market_id
            WHERE e.event_start_time > NOW()
        ";

        $result = $this->db->read($query);

        $tournaments = [];
        $sports = [];
        $countries = [];

        while ($row = $result->fetch_assoc()) {
            $sportId = $row['sport_id'];
            $countryId = $row['country_id'];
            $event_id = $row['event_id'];
            $tournament_id = $row['tournament_id'];
            $market_id = $row['market_id'];

            if (!isset($sports[$sportId])) {
                $sports[$sportId] = [
                    'sport_id' => $sportId,
                    'sport_name' => $row['sport_name'],
                    'count' => 0
                ];
            }

            if (!isset($countries[$countryId])) {
                $countries[$countryId] = [
                    'country_id' => $countryId,
                    'country_name' => $row['country_name']
                ];
            }

            if (!isset($tournaments[$tournament_id])) {
                $tournaments[$tournament_id] = [
                    'tournament_id' => $tournament_id,
                    'tournament_name' => $row['tournament_name'],
                    'sport_id' => $sportId,
                    'sport_name' => $row['sport_name'],
                    'country_id' => $countryId,
                    'country_name' => $row['country_name'],
                    'count' => 0,
                    'events' => []
                ];
            }

            if (!isset($tournaments[$tournament_id]['events'][$event_id])) {
                $tournaments[$tournament_id]['events'][$event_id] = [
                    'event_id' => $event_id,
                    'event_name' => $row['event_name'],
                    'event_start_time' => $row['event_start_time'],
                    'markets' => []
                ];

                $tournaments[$tournament_id]['count'] += 1;
                $sports[$sportId]['count'] += 1;
            }

            if (!isset($tournaments[$tournament_id]['events'][$event_id]['markets'][$market_id])) {
                $tournaments[$tournament_id]['events'][$event_id]['markets'][$market_id] = [
                    'market_id' => $market_id,
                    'market_type' => $row['market_type'],
                    'market_type_parameter' => $row['market_type_parameter'],
                    'scope_type' => $row['scope_type'],
                    'scope_number' => $row['scope_number'],
                    'outcomes' => []
                ];
            }

            $tournaments[$tournament_id]['events'][$event_id]['markets'][$market_id]['outcomes'][] = [
                'outcome_id' => $row['outcome_id'],
                'odds' => $row['odds']
            ];
        }

        $result->free();

        foreach ($tournaments as &$tournament) {
            $tournament['events'] = array_values($tournament['events']);

            foreach ($tournament['events'] as &$event) {
                $event['markets'] = array_values($event['markets']);
            }
        }

        return ['sports' => array_values($sports), 'countries' => array_values($countries), 'tournaments' => array_values($tournaments)];
    }
}