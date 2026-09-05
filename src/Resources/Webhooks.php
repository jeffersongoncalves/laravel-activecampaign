<?php

namespace JeffersonGoncalves\ActiveCampaign\Resources;

use InvalidArgumentException;
use JeffersonGoncalves\ActiveCampaign\ActiveCampaignClient;

class Webhooks
{
    public function __construct(
        protected ActiveCampaignClient $client,
        protected int $defaultLimit = 20,
    ) {}

    /** @param array<string, mixed> $params */
    public function list(array $params = []): array
    {
        return $this->client->get('/webhooks', array_merge([
            'limit' => $this->defaultLimit,
            'offset' => 0,
        ], $params));
    }

    public function get(int|string $id): array
    {
        return $this->client->get("/webhooks/{$id}");
    }

    /**
     * @param  array<int, string>  $events
     * @param  array<int, string>  $sources
     */
    public function create(
        string $name,
        string $url,
        array $events = ['subscribe'],
        array $sources = ['public', 'admin', 'api', 'system'],
    ): array {
        if ($name === '') {
            throw new InvalidArgumentException('The "name" attribute is required.');
        }

        if ($url === '') {
            throw new InvalidArgumentException('The "url" attribute is required.');
        }

        return $this->client->post('/webhooks', [
            'webhook' => [
                'name' => $name,
                'url' => $url,
                'events' => $events,
                'sources' => $sources,
            ],
        ]);
    }

    public function delete(int|string $id): array
    {
        return $this->client->delete("/webhooks/{$id}");
    }
}
