<?php

declare(strict_types=1);

namespace Homeapp\MonologSentryHandler;

use Sentry\Event as SentryEvent;
use Sentry\State\Scope;

class ScopeContextProcessor implements ScopeProcessor
{
    public function processScope(Scope $scope, array $record, SentryEvent $sentryEvent): void
    {
        if (isset($record['context']) && \is_array($record['context'])) {
            foreach ($record['context'] as $key => $value) {
                $scope->setExtra((string) $key, $value);
            }
        }
    }
}
