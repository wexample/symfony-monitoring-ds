<?php

namespace Wexample\SymfonyMonitoringDs\Traits;

use Wexample\SymfonyHelpers\Traits\BundleClassTrait;
use Wexample\SymfonyMonitoringDs\WexampleSymfonyMonitoringDsBundle;

trait SymfonyMonitoringDsBundleClassTrait
{
    use BundleClassTrait;

    public static function getBundleClassName(): string
    {
        return WexampleSymfonyMonitoringDsBundle::class;
    }
}
