<?php

namespace Wexample\SymfonyMonitoringDs\Controller\Pages;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Wexample\SymfonyLoader\Controller\AbstractPagesController;
use Wexample\SymfonyMonitoring\Enum\IncidentStatus;
use Wexample\SymfonyMonitoring\Repository\IncidentRepository;
use Wexample\SymfonyMonitoring\Service\HeartbeatService;
use Wexample\SymfonyMonitoringDs\Security\MonitoringAccessVoter;
use Wexample\SymfonyMonitoringDs\Traits\SymfonyMonitoringDsBundleClassTrait;

/**
 * The incidents — the active ones by default, a status at a time, the last seen
 * first — and the heartbeats; an incident on a page of its own, with what can
 * be decided about it. Access follows wexample_symfony_monitoring_ds.access_role.
 */
#[Route(path: '/monitoring/', name: 'monitoring_')]
#[IsGranted(MonitoringAccessVoter::ATTRIBUTE)]
final class MonitoringController extends AbstractPagesController
{
    use SymfonyMonitoringDsBundleClassTrait;

    public const string ROUTE_INDEX = 'monitoring_index';

    public const string ROUTE_INCIDENT_SHOW = 'monitoring_incident_show';

    /** The filter of the list: the active incidents, one status, or all. */
    public const string FILTER_ACTIVE = 'active';

    public const string FILTER_ALL = 'all';

    private const int PER_PAGE = 50;

    #[Route(path: '', name: 'index', methods: [Request::METHOD_GET])]
    public function index(
        Request $request,
        IncidentRepository $incidents,
        HeartbeatService $heartbeats,
    ): Response {
        $filter = $request->query->getString('status', self::FILTER_ACTIVE);
        $statuses = match (true) {
            self::FILTER_ALL === $filter => [],
            null !== IncidentStatus::tryFrom($filter) => [IncidentStatus::from($filter)],
            default => IncidentStatus::active(),
        };
        $pagesCount = max(1, (int) ceil($incidents->countByStatuses($statuses) / self::PER_PAGE));
        $page = min($pagesCount, max(1, $request->query->getInt('page', 1)));

        return $this->renderPage('index', [
            'incidents' => $incidents->findByStatuses($statuses, self::PER_PAGE, ($page - 1) * self::PER_PAGE),
            'counts' => $incidents->countPerStatus(),
            'statuses' => IncidentStatus::cases(),
            'page' => $page,
            'pages_count' => $pagesCount,
            'heartbeats' => $heartbeats->getStates(),
        ]);
    }

    #[Route(path: 'incidents/{id}', name: 'incident_show', requirements: ['id' => Requirement::UUID], methods: [Request::METHOD_GET])]
    public function incident(
        string $id,
        IncidentRepository $incidents,
    ): Response {
        return $this->renderPage('incident', [
            'incident' => $incidents->find($id) ?? throw $this->createNotFoundException(),
        ]);
    }
}
