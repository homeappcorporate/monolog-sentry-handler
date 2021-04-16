# Extension points

You can write your own `ScopeProcessor` implementations and pass them to `SentryHandler`, to fill SentryScope or SentryEvent with additional information:

```php
<?php

use Sentry\Event as SentryEvent;
use Homeapp\MonologSentryHandler\ScopeProcessor;
use Sentry\State\Scope;

class CustomScopeProcessor implements ScopeProcessor
{
    /** {@inheritdoc} */
    public function processScope(Scope $scope, array $record, SentryEvent $sentryEvent): void
    {
        // Your custom logic like this one:
        // ....
        if (isset($record['context']['extra']) && \is_array($record['context']['extra'])) {
            foreach ($record['context']['extra'] as $key => $value) {
                $scope->setExtra((string) $key, $value);
            }
        }

        if (isset($record['context']['tags']) && \is_array($record['context']['tags'])) {
            foreach ($record['context']['tags'] as $key => $value) {
                $scope->setTag($key, $value);
            }
        }
    }
}
```

Please look at these methods within [the code](../src/SentryHandler.php) if you want more details.
