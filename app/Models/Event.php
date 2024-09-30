<?php

declare(strict_types=1);

namespace App\Models;

class Event
{
    private string $event_id;

    private string $event_name;

    private string $event_start_time;

    /**
     * @var Scope[]
     */
    private array $scopes;

    /**
     * @param string $event_id
     * @param string $event_name
     * @param string $event_start_time
     * @param Scope[] $scopes
     */
    public function __construct(string $event_id, string $event_name, string $event_start_time, array $scopes)
    {
        $this->event_id = $event_id;
        $this->event_name = $event_name;
        $this->event_start_time = $event_start_time;
        $this->scopes = $scopes;
    }

    public function getEventId(): string
    {
        return $this->event_id;
    }

    public function getEventName(): string
    {
        return $this->event_name;
    }

    public function getEventStartTime(): string
    {
        return $this->event_start_time;
    }

    /**
     * @return Scope[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }
}