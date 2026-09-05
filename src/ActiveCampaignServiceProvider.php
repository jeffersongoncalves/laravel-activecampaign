<?php

namespace JeffersonGoncalves\ActiveCampaign;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ActiveCampaignServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('activecampaign')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(ActiveCampaign::class, function () {
            return new ActiveCampaign(
                (string) config('activecampaign.api_url'),
                (string) config('activecampaign.api_key'),
                (int) config('activecampaign.default_limit', 20),
            );
        });
    }
}
