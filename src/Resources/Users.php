<?php

namespace JeffersonGoncalves\ActiveCampaign\Resources;

use JeffersonGoncalves\ActiveCampaign\ActiveCampaignClient;

class Users
{
    public function __construct(
        protected ActiveCampaignClient $client,
    ) {}

    public function me(): array
    {
        return $this->client->get('/users/me');
    }

    public function list(): array
    {
        return $this->client->get('/users');
    }
}
