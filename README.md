# symfony-monitoring-ds

Version: 2.0.1

Design-system screens of symfony-monitoring: the incidents and their decisions, the heartbeats

## Table of Contents

- [Installation](#installation)
- [Introduction](#introduction)

## Installation

```php
// config/bundles.php
Wexample\SymfonyMonitoring\WexampleSymfonyMonitoringBundle::class => ['all' => true],
Wexample\SymfonyMonitoringDs\WexampleSymfonyMonitoringDsBundle::class => ['all' => true],
```

Who may see the incidents and decide about them is the application's call:

```yaml
# config/packages/wexample_symfony_monitoring_ds.yaml
wexample_symfony_monitoring_ds:
  access_role: ROLE_ADMIN   # the default
```

The controllers are routed by the application's `routing.controllers` import, like every package of the suite. The page is `/monitoring/` (route `monitoring_index`), an incident `/monitoring/incidents/{id}` (`monitoring_incident_show`); the decision endpoint is exposed to the JS router, so it must appear in the routes dumped for FOSJsRouting.

`symfony-monitoring-ds` is the screen of `symfony-monitoring`, built on the design system: the incidents — the active ones by default, a status at a time, the last seen first —, each on a page of its own with what it is, how often it happened, what was decided and by whom; the decisions left — acknowledge, resolve, ignore — with a note; and the heartbeats, late or on time.

Page and endpoint are restricted to a configurable role, `ROLE_ADMIN` by default. The alert symfony-monitoring sends links to the incident's page once this bundle is installed.
