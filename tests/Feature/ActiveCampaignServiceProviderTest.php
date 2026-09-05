<?php

use JeffersonGoncalves\ActiveCampaign\ActiveCampaign as ActiveCampaignManager;
use JeffersonGoncalves\ActiveCampaign\Facades\ActiveCampaign;

it('merges the default config', function () {
    expect(config('activecampaign.default_limit'))->toBe(20);
});

it('resolves the facade to the manager singleton', function () {
    expect(ActiveCampaign::getFacadeRoot())->toBeInstanceOf(ActiveCampaignManager::class);
});

it('always resolves the same manager instance', function () {
    expect(app(ActiveCampaignManager::class))->toBe(app(ActiveCampaignManager::class));
});
