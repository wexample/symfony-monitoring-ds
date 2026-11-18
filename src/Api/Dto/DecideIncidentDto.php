<?php

namespace Wexample\SymfonyMonitoringDs\Api\Dto;

use Symfony\Component\Validator\Constraints\Length;
use Wexample\SymfonyApi\Api\Dto\AbstractDto;

/**
 * A decision about an incident: what the person deciding has to say, if anything.
 */
class DecideIncidentDto extends AbstractDto
{
    #[Length(max: 2000)]
    public ?string $note = null;
}
