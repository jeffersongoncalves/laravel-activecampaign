<?php

namespace JeffersonGoncalves\ActiveCampaign\Tests;

use JeffersonGoncalves\ActiveCampaign\ActiveCampaignServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ActiveCampaignServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('activecampaign.api_url', 'https://example.api-us1.com');
        $app['config']->set('activecampaign.api_key', 'test-api-key');
    }
}
