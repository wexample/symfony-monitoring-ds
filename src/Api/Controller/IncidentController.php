<?php

namespace Wexample\SymfonyMonitoringDs\Api\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Wexample\SymfonyApi\Api\Attribute\ValidateRequestContent;
use Wexample\SymfonyApi\Api\Class\ApiResponse;
use Wexample\SymfonyApi\Api\Controller\AbstractApiController;
use Wexample\SymfonyHelpers\Controller\AbstractController;
use Wexample\SymfonyHelpers\Entity\Interfaces\UserEntityInterface;
use Wexample\SymfonyMonitoring\Repository\IncidentRepository;
use Wexample\SymfonyMonitoring\Service\IncidentService;
use Wexample\SymfonyMonitoringDs\Api\Dto\DecideIncidentDto;
use Wexample\SymfonyMonitoringDs\Security\MonitoringAccessVoter;

/**
 * What the incident screen calls: a decision — acknowledge, resolve, ignore —,
 * with a note, by the signed-in user.
 */
#[Route(path: '_api/monitoring/incident/', name: 'api_monitoring_incident_')]
#[IsGranted(MonitoringAccessVoter::ATTRIBUTE)]
class IncidentController extends AbstractApiController
{
    final public const string ROUTE_DECIDE = 'api_monitoring_incident_decide';

    #[Route(
        path: '{id}/{decision}',
        name: 'decide',
        requirements: ['decision' => 'acknowledge|resolve|ignore'],
        methods: AbstractController::ROUTE_OPTIONS_METHOD_ONLY_POST,
        options: AbstractController::ROUTE_OPTIONS_ONLY_EXPOSE
    )]
    #[ValidateRequestContent(dto: DecideIncidentDto::class, attributeName: 'decideIncidentDto')]
    public function decide(
        string $id,
        string $decision,
        DecideIncidentDto $decideIncidentDto,
        IncidentRepository $repository,
        IncidentService $incidents,
    ): ApiResponse {
        $incident = $repository->find($id);

        if (null === $incident) {
            return self::apiResponseError('Unknown incident.');
        }

        $user = $this->getUser();
        $by = $user instanceof UserEntityInterface ? $user : null;

        match ($decision) {
            'acknowledge' => $incidents->acknowledge($incident, $by, $decideIncidentDto->note),
            'resolve' => $incidents->resolve($incident, $by, $decideIncidentDto->note),
            'ignore' => $incidents->ignore($incident, $by, $decideIncidentDto->note),
        };

        return self::apiResponseSuccess(data: ['status' => $incident->getStatus()->value]);
    }
}
