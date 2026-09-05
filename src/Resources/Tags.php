<?php

namespace JeffersonGoncalves\ActiveCampaign\Resources;

use InvalidArgumentException;
use JeffersonGoncalves\ActiveCampaign\ActiveCampaignClient;

class Tags
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
            'limit' => $filters['limit'] ?? $this->defaultLimit,
            'offset' => $filters['offset'] ?? 0,
        ], fn (mixed $value) => $value !== null);

        return $this->client->get('/tags', $query);
    }

    public function get(int|string $id): array
    {
        return $this->client->get("/tags/{$id}");
    }

    public function create(string $name, string $type = 'contact'): array
    {
        if ($name === '') {
            throw new InvalidArgumentException('The tag "name" is required.');
        }

        return $this->client->post('/tags', ['tag' => ['tag' => $name, 'tagType' => $type]]);
    }

    public function delete(int|string $id): array
    {
        return $this->client->delete("/tags/{$id}");
    }

    public function addToContact(int|string $tagId, int|string $contactId): array
    {
        return $this->client->post('/contactTags', [
            'contactTag' => [
                'contact' => $contactId,
                'tag' => $tagId,
            ],
        ]);
    }

    public function removeFromContact(int|string $contactTagId): array
    {
        return $this->client->delete("/contactTags/{$contactTagId}");
    }
}
