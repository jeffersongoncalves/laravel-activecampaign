<?php

use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\Response as HttpResponse;
use JeffersonGoncalves\ActiveCampaign\Exceptions\ActiveCampaignException;

function fakeAcResponse(int $status, array $body): Response
{
    $psr = new GuzzleHttp\Psr7\Response($status, [], json_encode($body));

    return new HttpResponse($psr);
}

it('builds the exception message from the response "message" field', function () {
    $response = fakeAcResponse(404, ['message' => 'Contact not found']);

    $exception = ActiveCampaignException::fromResponse($response);

    expect($exception->getMessage())->toBe('Contact not found')
        ->and($exception->getCode())->toBe(404)
        ->and($exception->errorBody())->toBe(['message' => 'Contact not found']);
});

it('falls back to the first error title when "message" is missing', function () {
    $response = fakeAcResponse(422, ['errors' => [['title' => 'Email is invalid']]]);

    $exception = ActiveCampaignException::fromResponse($response);

    expect($exception->getMessage())->toBe('Email is invalid');
});

it('falls back to a generic message when the body has no known error keys', function () {
    $response = fakeAcResponse(500, []);

    $exception = ActiveCampaignException::fromResponse($response);

    expect($exception->getMessage())->toBe('ActiveCampaign API error (HTTP 500).');
});
