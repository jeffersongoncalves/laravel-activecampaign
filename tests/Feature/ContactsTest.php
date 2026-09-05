<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ActiveCampaign\Exceptions\ActiveCampaignException;
use JeffersonGoncalves\ActiveCampaign\Facades\ActiveCampaign;

it('lists contacts with filters', function () {
    Http::fake([
        '*/contacts*' => Http::response(['contacts' => [['id' => 1, 'email' => 'jane@example.com']]]),
    ]);

    $result = ActiveCampaign::contacts()->list(['email' => 'jane@example.com']);

    expect($result['contacts'][0]['email'])->toBe('jane@example.com');

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), '/api/3/contacts?')
        && str_contains((string) $request->url(), 'email=jane%40example.com')
        && $request->hasHeader('Api-Token', 'test-api-key'));
});

it('gets a single contact', function () {
    Http::fake(['*/contacts/1' => Http::response(['contact' => ['id' => 1]])]);

    $result = ActiveCampaign::contacts()->get(1);

    expect($result['contact']['id'])->toBe(1);
});

it('creates a contact', function () {
    Http::fake(['*/contacts' => Http::response(['contact' => ['id' => 1]], 201)]);

    $result = ActiveCampaign::contacts()->create(['email' => 'jane@example.com']);

    expect($result['contact']['id'])->toBe(1);
    Http::assertSent(fn ($request) => $request['contact']['email'] === 'jane@example.com');
});

it('requires an email to create a contact', function () {
    ActiveCampaign::contacts()->create([]);
})->throws(InvalidArgumentException::class, 'The "email" attribute is required.');

it('updates a contact', function () {
    Http::fake(['*/contacts/1' => Http::response(['contact' => ['id' => 1, 'firstName' => 'Jane']])]);

    $result = ActiveCampaign::contacts()->update(1, ['firstName' => 'Jane']);

    expect($result['contact']['firstName'])->toBe('Jane');
});

it('deletes a contact', function () {
    Http::fake(['*/contacts/1' => Http::response([])]);

    ActiveCampaign::contacts()->delete(1);

    Http::assertSent(fn ($request) => $request->method() === 'DELETE');
});

it('syncs a contact by email', function () {
    Http::fake(['*/contact/sync' => Http::response(['contact' => ['id' => 1]])]);

    $result = ActiveCampaign::contacts()->sync(['email' => 'jane@example.com']);

    expect($result['contact']['id'])->toBe(1);
});

it('requires an email to sync a contact', function () {
    ActiveCampaign::contacts()->sync([]);
})->throws(InvalidArgumentException::class);

it('throws an ActiveCampaignException on a failed request', function () {
    Http::fake(['*/contacts/1' => Http::response(['message' => 'Contact not found'], 404)]);

    ActiveCampaign::contacts()->get(1);
})->throws(ActiveCampaignException::class, 'Contact not found');
