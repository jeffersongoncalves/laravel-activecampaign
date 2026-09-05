<?php

namespace JeffersonGoncalves\ActiveCampaign\Exceptions;

use Illuminate\Http\Client\Response;
use RuntimeException;

class ActiveCampaignException extends RuntimeException
{
    /** @var array<string, mixed> */
    protected array $errorBody = [];

    public static function fromResponse(Response $response): self
    {
        $body = (array) ($response->json() ?? []);

        $message = $body['message']
            ?? ($body['errors'][0]['title'] ?? null)
            ?? "ActiveCampaign API error (HTTP {$response->status()}).";

        $exception = new self($message, $response->status());
        $exception->errorBody = $body;

        return $exception;
    }

    /** @return array<string, mixed> */
    public function errorBody(): array
    {
        return $this->errorBody;
    }
}
