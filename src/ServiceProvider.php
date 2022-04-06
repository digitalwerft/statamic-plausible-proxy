<?php

namespace Digitalwerft\PlausibleProxy;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [Tags\Plausible::class];
    protected $routes = [
        'web' => __DIR__ . '/../routes/web.php',
    ];
}
