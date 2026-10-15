<?php

// Read through config() so the values survive `php artisan config:cache`
// (env() returns null once the configuration is cached).
return [

    // Render the script tag at all (disable in development).
    'enabled' => env('PLAUSIBLE_ENABLED', false),

    // Domain the site is registered with in Plausible. Falls back to the host of APP_URL.
    'site' => env('PLAUSIBLE_SITE'),

    // Serve the script from /pio.js and forward events through /api/event.
    // When disabled, browsers talk to plausible.io directly and Plausible sees their full IP.
    'proxy_enabled' => env('PLAUSIBLE_PROXY_ENABLED', true),

];
