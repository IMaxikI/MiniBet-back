<?php

declare(strict_types=1);

namespace App\Models;

class Structure
{
    /**
     * @var Sport[]
     */
    private array $sports;

    /**
     * @var Country[]
     */
    private array $countries;

    /**
     * @var Tournament[]
     */
    private array $tournaments;

    public function __construct()
    {
        $this->sports = [];
        $this->countries = [];
        $this->tournaments = [];
    }

    /**
     * @return Sport[]
     */
    public function getSports(): array
    {
        return $this->sports;
    }

    /**
     * @return Country[]
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * @return Tournament[]
     */
    public function getTournaments(): array
    {
        return $this->tournaments;
    }

    public function addSport(Sport $sport): void
    {
        $sportId = $sport->getSportId();

        if (empty($this->sports[$sportId])) {
            $this->sports[$sportId] = $sport;
        }
    }

    public function addCountry(Country $country): void
    {
        $countryId = $country->getCountryId();

        if (empty($this->countries[$countryId])) {
            $this->countries[$countryId] = $country;
        }
    }

    public function addTournament(Tournament $tournament): void
    {
        $tournamentId = $tournament->getTournamentId();

        if (empty($this->tournaments[$tournamentId])) {
            $this->tournaments[$tournamentId] = $tournament;
        }
    }

    public function addEvent(Event $event, string $tournamentId): void
    {
        if(isset($this->tournaments[$tournamentId])) {
            $this->tournaments[$tournamentId]->addTournamentEvent($event);

        }
    }
}