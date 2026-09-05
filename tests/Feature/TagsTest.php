<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ActiveCampaign\Facades\ActiveCampaign;

it('lists tags', function () {
    Http::fake(['*/tags*' => Http::response(['tags' => [['id' => 1, 'tag' => 'vip']]])]);

    $result = ActiveCampaign::tags()->list(['search' => 'vip']);

    expect($result['tags'][0]['tag'])->toBe('vip');
});

it('gets a single tag', function () {
    Http::fake(['*/tags/1' => Http::response(['tag' => ['id' => 1]])]);

    $result = ActiveCampaign::tags()->get(1);

    expect($result['tag']['id'])->toBe(1);
});

it('creates a tag', function () {
    Http::fake(['*/tags' => Http::response(['tag' => ['id' => 1, 'tag' => 'vip']], 201)]);

    $result = ActiveCampaign::tags()->create('vip');

    expect($result['tag']['tag'])->toBe('vip');
    Http::assertSent(fn ($request) => $request['tag']['tagType'] === 'contact');
});

it('requires a non-empty name to create a tag', function () {
    ActiveCampaign::tags()->create('');
})->throws(InvalidArgumentException::class, 'The tag "name" is required.');

it('deletes a tag', function () {
    Http::fake(['*/tags/1' => Http::response([])]);

    ActiveCampaign::tags()->delete(1);

    Http::assertSent(fn ($request) => $request->method() === 'DELETE');
});

it('adds a tag to a contact', function () {
    Http::fake(['*/contactTags' => Http::response(['contactTag' => ['id' => 1, 'contact' => 2, 'tag' => 3]])]);

    $result = ActiveCampaign::tags()->addToContact(3, 2);

    expect($result['contactTag']['id'])->toBe(1);
});

it('removes a tag from a contact', function () {
    Http::fake(['*/contactTags/1' => Http::response([])]);

    ActiveCampaign::tags()->removeFromContact(1);

    Http::assertSent(fn ($request) => $request->method() === 'DELETE');
});
