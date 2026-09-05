<?php

namespace JeffersonGoncalves\ActiveCampaign\Resources;

use JeffersonGoncalves\ActiveCampaign\ActiveCampaignClient;

class Automations
{
    public function __construct(
        protected ActiveCampaignClient $client,
        protected int $defaultLimit = 20,
    ) {}

    /** @param array<string, mixed> $params */
    public function list(array $params = []): array
    {
        return $this->client->get('/automations', array_merge([
            'limit' => $this->defaultLimit,
            'offset' => 0,
        ], $params));
    }

    public function get(int|string $id): array
    {
        return $this->client->get("/automations/{$id}");
    }

    public function addContact(int|string $automationId, int|string $contactId): array
    {
        return $this->client->post('/contactAutomations', [
            'contactAutomation' => [
                'contact' => $contactId,
                'automation' => $automationId,
            ],
        ]);
    }
}
