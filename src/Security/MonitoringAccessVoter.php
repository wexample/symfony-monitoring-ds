<?php

namespace Wexample\SymfonyMonitoringDs\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Grants the monitoring screens to whoever holds the configured role, so each
 * app decides who sees the incidents while the controllers name a single attribute.
 */
final class MonitoringAccessVoter extends Voter
{
    public const string ATTRIBUTE = 'MONITORING_ACCESS';

    public function __construct(
        private readonly Security $security,
        #[Autowire('%wexample_symfony_monitoring_ds.access_role%')]
        private readonly string $accessRole,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::ATTRIBUTE === $attribute;
    }

    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token,
        ?Vote $vote = null
    ): bool {
        return $this->security->isGranted($this->accessRole);
    }
}
