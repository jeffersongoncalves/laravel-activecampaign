<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ActiveCampaign\Facades\ActiveCampaign;

it('lists pipelines', function () {
    Http::fake(['*/dealGroups*' => Http::response(['dealGroups' => [['id' => 1, 'title' => 'Sales']]])]);

    $result = ActiveCampaign::pipelines()->list();

    expect($result['dealGroups'][0]['title'])->toBe('Sales');
});

it('gets a single pipeline', function () {
    Http::fake(['*/dealGroups/1' => Http::response(['dealGroup' => ['id' => 1]])]);

    $result = ActiveCampaign::pipelines()->get(1);

    expect($result['dealGroup']['id'])->toBe(1);
});
