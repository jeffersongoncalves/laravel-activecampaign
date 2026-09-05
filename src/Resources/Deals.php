<?php

namespace JeffersonGoncalves\ActiveCampaign\Resources;

use InvalidArgumentException;
use JeffersonGoncalves\ActiveCampaign\ActiveCampaignClient;

class Deals
{
    public function __construct(
        protected ActiveCampaignClient $client,
        protected int $defaultLimit = 20,
    ) {}

    /** @param array<string, mixed> $filters */
    public function list(array $filters = []): array
    {
        $query = array_filter([
            'search' => $filters['search'] ?? null,
            'filters[stage]' => $filters['stage'] ?? null,
            'filters[owner]' => $filters['owner'] ?? null,
            'limit' => $filters['limit'] ?? $this->defaultLimit,
            'offset' => $filters['offset'] ?? 0,
        ], fn (mixed $value) => $value !== null);

        return $this->client->get('/deals', $query);
    }

    public function get(int|string $id): array
    {
        return $this->client->get("/deals/{$id}");
    }

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): array
    {
        if (empty($attributes['title'])) {
            throw new InvalidArgumentException('The "title" attribute is required.');
        }

        return $this->client->post('/deals', ['deal' => $attributes]);
    }

    /** @param array<string, mixed> $attributes */
    public function update(int|string $id, array $attributes): array
    {
        return $this->client->put("/deals/{$id}", ['deal' => $attributes]);
    }

    public function delete(int|string $id): array
    {
        return $this->client->delete("/deals/{$id}");
    }
}
