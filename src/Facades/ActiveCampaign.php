<?php

namespace JeffersonGoncalves\ActiveCampaign\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeffersonGoncalves\ActiveCampaign\ActiveCampaign
 */
class ActiveCampaign extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JeffersonGoncalves\ActiveCampaign\ActiveCampaign::class;
    }
}
