<?php

namespace JeffersonGoncalves\ActiveCampaign\Resources;

use JeffersonGoncalves\ActiveCampaign\ActiveCampaignClient;

class Campaigns
{
    public function __construct(
        protected ActiveCampaignClient $client,
        protected int $defaultLimit = 20,
    ) {}

    /** @param array<string, mixed> $params */
    public function list(array $params = []): array
    {
        return $this->client->get('/campaigns', array_merge([
            'limit' => $this->defaultLimit,
            'offset' => 0,
        ], $params));
    }

    public function get(int|string $id): array
    {
        return $this->client->get("/campaigns/{$id}");
    }
}
