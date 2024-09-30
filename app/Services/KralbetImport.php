<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\IImport;
use App\Interfaces\Repositories\ICountryRepository;
use App\Interfaces\Repositories\IEventRepository;
use App\Interfaces\Repositories\IMarketRepository;
use App\Interfaces\Repositories\IOutcomeRepository;
use App\Interfaces\Repositories\ISportRepository;
use App\Interfaces\Repositories\ITournamentRepository;
use App\Models\Structure;
use App\Models\Tournament;

class KralbetImport implements IImport
{
    private ICountryRepository $countryRepository;

    private ISportRepository $sportRepository;

    private ITournamentRepository $tournamentRepository;

    private IEventRepository $eventRepository;

    private IMarketRepository $marketRepository;

    private IOutcomeRepository $outcomeRepository;

    public function __construct(
        ICountryRepository    $countryRepository,
        ISportRepository      $sportRepository,
        ITournamentRepository $tournamentRepository,
        IEventRepository      $eventRepository,
        IMarketRepository     $marketRepository,
        IOutcomeRepository    $outcomeRepository,
    )
    {
        $this->countryRepository = $countryRepository;
        $this->sportRepository = $sportRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->eventRepository = $eventRepository;
        $this->marketRepository = $marketRepository;
        $this->outcomeRepository = $outcomeRepository;
    }

    public function import(Structure $structure): void
    {
        $sports = $structure->getSports();
        $countries = $structure->getCountries();
        $tournaments = $structure->getTournaments();

        $this->sportRepository->saveMany($sports);
        $this->countryRepository->saveMany($countries);
        $this->tournamentRepository->saveMany($tournaments);
        $this->saveRelatedData($tournaments);
    }

    /**
     * @param Tournament[] $tournaments
     * @return void
     */
    public function saveRelatedData(array $tournaments): void
    {
        $valuesEvents = [];
        $sizeEvents = 0;

        $valuesMarket = [];
        $sizeMarket = 0;

        $valuesOutcome = [];
        $sizeOutcome = 0;

        foreach ($tournaments as $tournament) {
            $events = $tournament->getEvents();
            $sizeEvents += count($events);

            foreach ($events as $event) {
                array_push($valuesEvents,
                    $event->getEventId(), $tournament->getTournamentId(),
                    $event->getEventName(), $event->getEventStartTime()
                );

                foreach ($event->getScopes() as $scope) {
                    $markets = $scope->getMarkets();
                    $sizeMarket += count($markets);

                    foreach ($markets as $market) {
                        array_push($valuesMarket,
                            $market->getMarketId(), $event->getEventId(), $market->getMarketType(),
                            $market->getMarketTypeParameter(), $scope->getScopeType(), $scope->getScopeNumber()
                        );

                        $outcomes = $market->getOutcomes();
                        $sizeOutcome += count($outcomes);

                        foreach ($outcomes as $outcome) {
                            array_push($valuesOutcome,
                                $outcome->getOutcomeId(), $market->getMarketId(), $outcome->getOdds()
                            );
                        }
                    }
                }
            }
        }

        if($sizeEvents && $sizeMarket && $sizeOutcome) {
            $this->eventRepository->savePreparedData($valuesEvents, $sizeEvents);
            $this->marketRepository->savePreparedData($valuesMarket, $sizeMarket);
            $this->outcomeRepository->savePreparedData($valuesOutcome, $sizeOutcome);
        }
    }
}