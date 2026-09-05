<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ActiveCampaign\Facades\ActiveCampaign;

it('gets the authenticated user', function () {
    Http::fake(['*/users/me' => Http::response(['user' => ['username' => 'jefferson']])]);

    $result = ActiveCampaign::users()->me();

    expect($result['user']['username'])->toBe('jefferson');
});

it('lists users', function () {
    Http::fake(['*/users' => Http::response(['users' => [['id' => 1]]])]);

    $result = ActiveCampaign::users()->list();

    expect($result['users'][0]['id'])->toBe(1);
});
