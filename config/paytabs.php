<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Merchant profile id
    |--------------------------------------------------------------------------
    |
    | Your merchant profile id , you can find the profile id on your PayTabs Merchant’s Dashboard-profile.
    |
    */

    'profile_id' => env('PAYTABS_PROFILE_ID'),

    /*
    |--------------------------------------------------------------------------
    | Server Key
    |--------------------------------------------------------------------------
    |
    | You can find the Server key on your PayTabs Merchant’s Dashboard - Developers - Key management.
    |
    */

    'server_key' => env('PAYTABS_SERVER_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | The currency you registered in with PayTabs account
    | Supported: "AED", "EGP", "SAR", "OMR", "JOD", "US"
    |
    */

    'currency' => env('PAYTABS_CURRENCY'),

    /*
    |--------------------------------------------------------------------------
    | Region
    |--------------------------------------------------------------------------
    |
    | The region you registered in with PayTabs
    | Supported: "ARE", "EGY", "SAU", "OMN", "JOR", "GLOBAL"
    |
    */

    'region' => env('PAYTABS_REGION'),

    /*
    |--------------------------------------------------------------------------
    | IFrame Message Target
    |--------------------------------------------------------------------------
    |
    | A valid HTTPS website URL of your domain (the recipient) that will receive the event.
    | In order for the event to be dispatched,
    | this domain must match exactly (including scheme, hostname, and port).
    |
    */

    'iframe_message_target' => env('PAYTABS_IFRAME_MESSAGE_TARGET'),
];
