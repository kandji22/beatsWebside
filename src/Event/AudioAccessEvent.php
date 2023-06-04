<?php
// src/Event/AudioAccessEvent.php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class AudioAccessEvent extends Event
{
    private $requestPath;

    public function __construct(string $requestPath)
    {
        $this->requestPath = $requestPath;
    }

    public function getRequestPath(): string
    {
        return $this->requestPath;
    }
}
