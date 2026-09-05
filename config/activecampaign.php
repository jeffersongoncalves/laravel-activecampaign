<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ActiveCampaign API URL
    |--------------------------------------------------------------------------
    |
    | Your account's own API URL, e.g. https://youraccountname.api-us1.com.
    | Find it under Settings > Developer in your ActiveCampaign account.
    |
    */
    'api_url' => env('ACTIVECAMPAIGN_API_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | ActiveCampaign API Key
    |--------------------------------------------------------------------------
    */
    'api_key' => env('ACTIVECAMPAIGN_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Pagination Limit
    |--------------------------------------------------------------------------
    |
    | Used as the default "limit" for list endpoints when none is given.
    |
    */
    'default_limit' => env('ACTIVECAMPAIGN_DEFAULT_LIMIT', 20),

];
