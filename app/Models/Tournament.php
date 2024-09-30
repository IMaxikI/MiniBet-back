<?php

declare(strict_types=1);

namespace App\Models;

class Tournament
{
    private string $tournament_name;

    private string $tournament_id;

    private Sport $sport;

    private Country $country;

    /**
     * @var Event[]
     */
    private array $events;

    /**
     * @param string $tournament_name
     * @param string $tournament_id
     * @param Sport $sport
     * @param Country $country
     * @param Event[] $events
     */
    public function __construct(string $tournament_name, string $tournament_id, Sport $sport, Country $country, array $events = [])
    {
        $this->tournament_name = $tournament_name;
        $this->tournament_id = $tournament_id;
        $this->sport = $sport;
        $this->country = $country;
        $this->events = $events;
    }

    public function getTournamentId(): string
    {
        return $this->tournament_id;
    }

    public function getTournamentName(): string
    {
        return $this->tournament_name;
    }

    public function getSport(): Sport
    {
        return $this->sport;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * @return Event[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    public function addTournamentEvent(Event $event): void
    {
        $this->events[] = $event;
    }
}