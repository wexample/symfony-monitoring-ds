<?php

namespace Wexample\SymfonyMonitoringDs\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Wexample\SymfonyHelpers\Helper\RoleHelper;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('wexample_symfony_monitoring_ds');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('access_role')
                    ->defaultValue(RoleHelper::ROLE_ADMIN)
                    ->info('Role required to see the incidents and decide about them.')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
