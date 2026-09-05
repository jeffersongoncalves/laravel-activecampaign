<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ActiveCampaign\Facades\ActiveCampaign;

it('lists automations', function () {
    Http::fake(['*/automations*' => Http::response(['automations' => [['id' => 1, 'name' => 'Welcome']]])]);

    $result = ActiveCampaign::automations()->list();

    expect($result['automations'][0]['name'])->toBe('Welcome');
});

it('gets a single automation', function () {
    Http::fake(['*/automations/1' => Http::response(['automation' => ['id' => 1]])]);

    $result = ActiveCampaign::automations()->get(1);

    expect($result['automation']['id'])->toBe(1);
});

it('adds a contact to an automation', function () {
    Http::fake(['*/contactAutomations' => Http::response(['contactAutomation' => ['contact' => 2, 'automation' => 1]])]);

    $result = ActiveCampaign::automations()->addContact(1, 2);

    expect($result['contactAutomation']['automation'])->toBe(1);
    Http::assertSent(fn ($request) => $request['contactAutomation']['contact'] === 2);
});
