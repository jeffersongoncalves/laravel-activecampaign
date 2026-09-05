<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ActiveCampaign\Facades\ActiveCampaign;

it('lists deals with filters', function () {
    Http::fake(['*/deals*' => Http::response(['deals' => [['id' => 1, 'title' => 'Big Sale']]])]);

    $result = ActiveCampaign::deals()->list(['search' => 'Big Sale', 'stage' => 3]);

    expect($result['deals'][0]['title'])->toBe('Big Sale');
    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'filters%5Bstage%5D=3'));
});

it('gets a single deal', function () {
    Http::fake(['*/deals/1' => Http::response(['deal' => ['id' => 1]])]);

    $result = ActiveCampaign::deals()->get(1);

    expect($result['deal']['id'])->toBe(1);
});

it('creates a deal', function () {
    Http::fake(['*/deals' => Http::response(['deal' => ['id' => 1, 'title' => 'Big Sale']], 201)]);

    $result = ActiveCampaign::deals()->create(['title' => 'Big Sale', 'value' => 10000, 'currency' => 'usd']);

    expect($result['deal']['title'])->toBe('Big Sale');
});

it('requires a title to create a deal', function () {
    ActiveCampaign::deals()->create([]);
})->throws(InvalidArgumentException::class, 'The "title" attribute is required.');

it('updates a deal', function () {
    Http::fake(['*/deals/1' => Http::response(['deal' => ['id' => 1, 'stage' => 5]])]);

    $result = ActiveCampaign::deals()->update(1, ['stage' => 5]);

    expect($result['deal']['stage'])->toBe(5);
});

it('deletes a deal', function () {
    Http::fake(['*/deals/1' => Http::response([])]);

    ActiveCampaign::deals()->delete(1);

    Http::assertSent(fn ($request) => $request->method() === 'DELETE');
});
