<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ActiveCampaign\Facades\ActiveCampaign;

it('lists webhooks', function () {
    Http::fake(['*/webhooks*' => Http::response(['webhooks' => [['id' => 1, 'name' => 'CRM Sync']]])]);

    $result = ActiveCampaign::webhooks()->list();

    expect($result['webhooks'][0]['name'])->toBe('CRM Sync');
});

it('gets a single webhook', function () {
    Http::fake(['*/webhooks/1' => Http::response(['webhook' => ['id' => 1]])]);

    $result = ActiveCampaign::webhooks()->get(1);

    expect($result['webhook']['id'])->toBe(1);
});

it('creates a webhook with default events and sources', function () {
    Http::fake(['*/webhooks' => Http::response(['webhook' => ['id' => 1, 'name' => 'CRM Sync']], 201)]);

    $result = ActiveCampaign::webhooks()->create('CRM Sync', 'https://example.com/hook');

    expect($result['webhook']['name'])->toBe('CRM Sync');
    Http::assertSent(fn ($request) => $request['webhook']['events'] === ['subscribe']
        && $request['webhook']['sources'] === ['public', 'admin', 'api', 'system']);
});

it('requires a name to create a webhook', function () {
    ActiveCampaign::webhooks()->create('', 'https://example.com/hook');
})->throws(InvalidArgumentException::class, 'The "name" attribute is required.');

it('requires a url to create a webhook', function () {
    ActiveCampaign::webhooks()->create('CRM Sync', '');
})->throws(InvalidArgumentException::class, 'The "url" attribute is required.');

it('deletes a webhook', function () {
    Http::fake(['*/webhooks/1' => Http::response([])]);

    ActiveCampaign::webhooks()->delete(1);

    Http::assertSent(fn ($request) => $request->method() === 'DELETE');
});
