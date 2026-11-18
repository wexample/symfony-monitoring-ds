`symfony-monitoring-ds` is the screen of `symfony-monitoring`, built on the design system: the incidents — the active ones by default, a status at a time, the last seen first —, each on a page of its own with what it is, how often it happened, what was decided and by whom; the decisions left — acknowledge, resolve, ignore — with a note; and the heartbeats, late or on time.

Page and endpoint are restricted to a configurable role, `ROLE_ADMIN` by default. The alert symfony-monitoring sends links to the incident's page once this bundle is installed.
