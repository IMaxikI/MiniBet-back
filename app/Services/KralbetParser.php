<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\IParser;
use App\Models\Country;
use App\Models\Event;
use App\Models\Market;
use App\Models\Outcome;
use App\Models\Scope;
use App\Models\Sport;
use App\Models\Structure;
use App\Models\Tournament;
use WebSocket\Client;

class KralbetParser implements IParser
{
    private Client $socket;

    private string $subscribe;

    public function __construct(Client $socket, string $subscribe)
    {
        $this->socket = $socket;
        $this->subscribe = $subscribe;
    }

    public function parse(): Structure
    {
        $this->socket->send($this->subscribe);

        $structure = new Structure();

        while (true) {
            try {
                $message = $this->socket->receive();

                if (str_starts_with($message, '42')) {
                    $jsonData = substr($message, 2);
                    $data = json_decode($jsonData, true);

                    if ($data === null) {
                        echo "Ошибка при парсинге JSON: " . json_last_error_msg();
                        exit;
                    }

                    $events = $data[1]['events'];

                    foreach ($events as $event) {
                        $eventData = $event['data'];

                        $sport = new Sport($eventData['sport']['id'], $eventData['sport']['name'], (int)$eventData['sport']['o']);
                        $structure->addSport($sport);

                        $country = new Country($eventData['country']['id'], $eventData['country']['name'], (int)$eventData['country']['o']);
                        $structure->addCountry($country);

                        $tournament = new Tournament(
                            $eventData['tournament']['name'],
                            $eventData['tournament']['id'],
                            $sport,
                            $country
                        );
                        $structure->addTournament($tournament);

                        $scopes = [];

                        foreach ($event['group_markets'] as $keyMarket => $markets) {
                            $marketsScope = [];

                            foreach ($markets as $marketData) {
                                $marketParts = explode('|', $marketData);
                                $outcomeParts = explode('!', $marketParts[7]);
                                $outcomes = [];

                                foreach ($outcomeParts as $outcome) {
                                    $parts = explode('~', $outcome);
                                    $outcomes[] = new Outcome((float)$parts[2], $parts[0]);
                                }

                                $marketsScope[] = new Market($marketParts[0], $marketParts[1], (float)$marketParts[2], $outcomes);
                            }

                            [$type, $number] = explode('|', $keyMarket);
                            $scopes[] = new Scope($type, (int)$number, $marketsScope);
                        }

                        $eventTournament = new Event($eventData['id'], $eventData['name'], $eventData['time'], $scopes);
                        $structure->addEvent($eventTournament, $tournament->getTournamentId());
                    }

                    break;
                }
            } catch (\WebSocket\ConnectionException $e) {
                echo $e->getMessage() . PHP_EOL;
                exit;
            }
        }

        $this->socket->close();

        return $structure;
    }
}