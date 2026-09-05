<?php

namespace JeffersonGoncalves\ActiveCampaign;

use JeffersonGoncalves\ActiveCampaign\Resources\Automations;
use JeffersonGoncalves\ActiveCampaign\Resources\Campaigns;
use JeffersonGoncalves\ActiveCampaign\Resources\Contacts;
use JeffersonGoncalves\ActiveCampaign\Resources\Deals;
use JeffersonGoncalves\ActiveCampaign\Resources\Lists;
use JeffersonGoncalves\ActiveCampaign\Resources\Pipelines;
use JeffersonGoncalves\ActiveCampaign\Resources\Tags;
use JeffersonGoncalves\ActiveCampaign\Resources\Users;
use JeffersonGoncalves\ActiveCampaign\Resources\Webhooks;

/**
 * Entry point exposing one resource per ActiveCampaign API v3 group.
 */
class ActiveCampaign
{
    protected ActiveCampaignClient $client;

    public function __construct(string $apiUrl, string $apiKey, protected int $defaultLimit = 20)
    {
        $this->client = new ActiveCampaignClient($apiUrl, $apiKey);
    }

    public function contacts(): Contacts
    {
        return new Contacts($this->client, $this->defaultLimit);
    }

    public function lists(): Lists
    {
        return new Lists($this->client, $this->defaultLimit);
    }

    public function campaigns(): Campaigns
    {
        return new Campaigns($this->client, $this->defaultLimit);
    }

    public function deals(): Deals
    {
        return new Deals($this->client, $this->defaultLimit);
    }

    public function automations(): Automations
    {
        return new Automations($this->client, $this->defaultLimit);
    }

    public function tags(): Tags
    {
        return new Tags($this->client, $this->defaultLimit);
    }

    public function pipelines(): Pipelines
    {
        return new Pipelines($this->client, $this->defaultLimit);
    }

    public function webhooks(): Webhooks
    {
        return new Webhooks($this->client, $this->defaultLimit);
    }

    public function users(): Users
    {
        return new Users($this->client);
    }
}
