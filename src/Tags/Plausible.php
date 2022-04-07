<?php

namespace Digitalwerft\PlausibleProxy\Tags;

use Statamic\Tags\Tags;
use \Illuminate\Support\Facades\Config;

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
        if (!env('PLAUSIBLE_ENABLED', false)) {
            return '';
        }

        $domain = env('PLAUSIBLE_SITE') ?? str_replace(['https://', 'http://'], '', Config::get('app.url'));
        $src = env('PLAUSIBLE_PROXY_ENABLED') ? '/pio.js' : 'https://plausible.io/js/plausible.js';
        $tag = '<script defer data-domain="' . $domain . '" src="' . $src . '"></script>';

        return $tag;
    }
}
