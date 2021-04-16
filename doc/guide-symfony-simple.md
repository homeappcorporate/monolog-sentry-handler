# Symfony guide

This guide proposed an simple solution to integrate Sentry in a Symfony project.
It'll not provide profit of using a `FingersCrossedHandler` with breadcrumbs, but will
allow to send all the context logged with error, and implement custom `ScopeContextProcessor`'s

## Step 1: Configure Sentry Hub

Symfony http client is suggested because of its native async capabilities.
Also, symfony sentry bundle will be used in this guide

```
composer require symfony/http-client nyholm/psr7 guzzlehttp/promises
compose require sentry/sentry-symfony
```

Then write following in the `sentry.yaml`(or customize it for you own needs):

```yaml
sentry:
    dsn: '%env(SENTRY_DSN)%'
    register_error_listener: true
   # options:
     # Optional parameter to keep releases in Sentry. You'll need to deal with updating env value each release in you deploying pipeline
     # release: '%env(RELEASE)%'

services:
    monolog.sentry.handler:
        class: \Homeapp\MonologSentryHandler\SentryHandler
        arguments:
            $hub: '@Sentry\State\HubInterface'
            $level: !php/const Monolog\Logger::ERROR
            $bubble: false
            $scopeProcessors: !tagged scope.processor

    Homeapp\MonologSentryHandler\ScopeContextProcessor:
        tags: [scope.processor]

    Monolog\Processor\PsrLogMessageProcessor:
        tags: { name: monolog.processor, handler: sentry }
```

## Step 2: Configure Monolog

```yaml
# config/packages/prod/monolog.yaml
monolog:
    handlers:
        # [...]
        sentry:
            type: service
            id: "monolog.sentry.handler"
        # [...]
```
