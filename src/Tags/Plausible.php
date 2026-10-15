<?php

namespace Digitalwerft\PlausibleProxy\Tags;

use Statamic\Tags\Tags;
use Illuminate\Support\Facades\Config;

/**
 * Tag to render a link tag to the plausible script.
 */
class Plausible extends Tags
{
    /**
     * The {{ plausible }} tag.
     *
     * @return string|array
     */
    public function index()
    {
        if (!Config::get('plausible-proxy.enabled')) {
            return '';
        }

        $domain = Config::get('plausible-proxy.site') ?: str_replace(['https://', 'http://'], '', Config::get('app.url'));
        $src = Config::get('plausible-proxy.proxy_enabled') ? '/pio.js' : 'https://plausible.io/js/plausible.js';
        $tag = '<script defer data-domain="' . e($domain) . '" src="' . $src . '"></script>';

        return $tag;
    }
}
