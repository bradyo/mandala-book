<?php
namespace Mandala\Analytics;

use Mandala\Application\BaseModule;
use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;

class Module extends BaseModule implements ConsoleBannerProviderInterface, ConsoleUsageProviderInterface
{
    protected $namespace = __NAMESPACE__;
    protected $modulePath = __DIR__;

    public function getConsoleBanner(AdapterInterface $console)
    {
        return 'Mandala Analytics';
    }

    public function getConsoleUsage(AdapterInterface $console)
    {
        return array(
            'analytics_weekly_aggregation' => 'Run weekly analytics aggregation',
        );
    }
}