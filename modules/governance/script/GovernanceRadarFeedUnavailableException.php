<?php

namespace App\Governance;

use InvalidArgumentException;

final class GovernanceRadarFeedUnavailableException extends InvalidArgumentException
{
    public function __construct(
        public readonly int $httpStatus,
        string $message = '',
    ) {
        parent::__construct($message !== '' ? $message : 'Feed URL is unavailable: HTTP '.$httpStatus);
    }

    public function isGone(): bool
    {
        return in_array($this->httpStatus, [404, 410], true);
    }

    public function isBlocked(): bool
    {
        return in_array($this->httpStatus, [401, 403], true);
    }

    public function syncStatus(): string
    {
        if ($this->isGone()) {
            return 'gone';
        }
        if ($this->isBlocked()) {
            return 'blocked';
        }

        return 'error';
    }
}
