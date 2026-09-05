<?php

namespace JeffersonGoncalves\ActiveCampaign\Resources;

use InvalidArgumentException;
use JeffersonGoncalves\ActiveCampaign\ActiveCampaignClient;

class Contacts
{
    public function __construct(
        protected ActiveCampaignClient $client,
        protected int $defaultLimit = 20,
    ) {}

    /** @param array<string, mixed> $filters */
    public function list(array $filters = []): array
    {
        $query = array_filter([
            'email' => $filters['email'] ?? null,
            'search' => $filters['search'] ?? null,
            'listid' => $filters['listid'] ?? null,
            'status' => $filters['status'] ?? null,
            'limit' => $filters['limit'] ?? $this->defaultLimit,
            'offset' => $filters['offset'] ?? 0,
        ], fn (mixed $value) => $value !== null);

        return $this->client->get('/contacts', $query);
    }

    public function get(int|string $id): array
    {
        return $this->client->get("/contacts/{$id}");
    }

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): array
    {
        if (empty($attributes['email'])) {
            throw new InvalidArgumentException('The "email" attribute is required.');
        }

        return $this->client->post('/contacts', ['contact' => $attributes]);
    }

    /** @param array<string, mixed> $attributes */
    public function update(int|string $id, array $attributes): array
    {
        return $this->client->put("/contacts/{$id}", ['contact' => $attributes]);
    }

    public function delete(int|string $id): array
    {
        return $this->client->delete("/contacts/{$id}");
    }

    /** @param array<string, mixed> $attributes */
    public function sync(array $attributes): array
    {
        if (empty($attributes['email'])) {
            throw new InvalidArgumentException('The "email" attribute is required.');
        }

        return $this->client->post('/contact/sync', ['contact' => $attributes]);
    }
}
