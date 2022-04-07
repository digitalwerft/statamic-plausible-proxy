<?php

namespace Digitalwerft\PlausibleProxy;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    // register plausible-tag
    protected $tags = [Tags\Plausible::class];
    // register routes for api and js-file
    protected $routes = [
        'web' => __DIR__ . '/../routes/web.php',
    ];
}
