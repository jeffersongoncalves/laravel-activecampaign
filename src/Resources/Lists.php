<?php

namespace JeffersonGoncalves\ActiveCampaign\Resources;

use InvalidArgumentException;
use JeffersonGoncalves\ActiveCampaign\ActiveCampaignClient;

class Lists
{
    public function __construct(
        protected ActiveCampaignClient $client,
        protected int $defaultLimit = 20,
    ) {}

    /** @param array<string, mixed> $params */
    public function list(array $params = []): array
    {
        return $this->client->get('/lists', array_merge([
            'limit' => $this->defaultLimit,
            'offset' => 0,
        ], $params));
    }

    public function get(int|string $id): array
    {
        return $this->client->get("/lists/{$id}");
    }

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): array
    {
        if (empty($attributes['name'])) {
            throw new InvalidArgumentException('The "name" attribute is required.');
        }

        return $this->client->post('/lists', ['list' => $attributes]);
    }

    public function delete(int|string $id): array
    {
        return $this->client->delete("/lists/{$id}");
    }

    public function subscribe(int|string $listId, int|string $contactId): array
    {
        return $this->client->post('/contactLists', [
            'contactList' => [
                'list' => $listId,
                'contact' => $contactId,
                'status' => 1,
            ],
        ]);
    }

    public function unsubscribe(int|string $listId, int|string $contactId): array
    {
        return $this->client->post('/contactLists', [
            'contactList' => [
                'list' => $listId,
                'contact' => $contactId,
                'status' => 2,
            ],
        ]);
    }
}
