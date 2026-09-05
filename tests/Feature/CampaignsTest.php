<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ActiveCampaign\Facades\ActiveCampaign;

it('lists campaigns', function () {
    Http::fake(['*/campaigns*' => Http::response(['campaigns' => [['id' => 1, 'name' => 'Launch']]])]);

    $result = ActiveCampaign::campaigns()->list();

    expect($result['campaigns'][0]['name'])->toBe('Launch');
});

it('gets a single campaign', function () {
    Http::fake(['*/campaigns/1' => Http::response(['campaign' => ['id' => 1]])]);

    $result = ActiveCampaign::campaigns()->get(1);

    expect($result['campaign']['id'])->toBe(1);
});
