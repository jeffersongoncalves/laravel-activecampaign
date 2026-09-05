<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ActiveCampaign\Facades\ActiveCampaign;

it('lists lists', function () {
    Http::fake(['*/lists*' => Http::response(['lists' => [['id' => 1, 'name' => 'Newsletter']]])]);

    $result = ActiveCampaign::lists()->list();

    expect($result['lists'][0]['name'])->toBe('Newsletter');
});

it('gets a single list', function () {
    Http::fake(['*/lists/1' => Http::response(['list' => ['id' => 1]])]);

    $result = ActiveCampaign::lists()->get(1);

    expect($result['list']['id'])->toBe(1);
});

it('creates a list', function () {
    Http::fake(['*/lists' => Http::response(['list' => ['id' => 1, 'name' => 'Newsletter']], 201)]);

    $result = ActiveCampaign::lists()->create(['name' => 'Newsletter']);

    expect($result['list']['name'])->toBe('Newsletter');
});

it('requires a name to create a list', function () {
    ActiveCampaign::lists()->create([]);
})->throws(InvalidArgumentException::class, 'The "name" attribute is required.');

it('deletes a list', function () {
    Http::fake(['*/lists/1' => Http::response([])]);

    ActiveCampaign::lists()->delete(1);

    Http::assertSent(fn ($request) => $request->method() === 'DELETE');
});

it('subscribes a contact to a list', function () {
    Http::fake(['*/contactLists' => Http::response(['contactList' => ['list' => 1, 'contact' => 2, 'status' => 1]])]);

    $result = ActiveCampaign::lists()->subscribe(1, 2);

    expect($result['contactList']['status'])->toBe(1);
    Http::assertSent(fn ($request) => $request['contactList']['status'] === 1);
});

it('unsubscribes a contact from a list', function () {
    Http::fake(['*/contactLists' => Http::response(['contactList' => ['list' => 1, 'contact' => 2, 'status' => 2]])]);

    $result = ActiveCampaign::lists()->unsubscribe(1, 2);

    expect($result['contactList']['status'])->toBe(2);
    Http::assertSent(fn ($request) => $request['contactList']['status'] === 2);
});
