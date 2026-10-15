# Statamic Plausible Proxy

> Statamic Plausible Proxy is a Statamic addon that simply includes a link tag to plausible. If neccessary events will be proxied so ad-blockers won't block targeting.

## Features
- `{{ plausible }}` tag for including a link tag to plausible in the `<head>` of the document.
- Can be used to proxy event requests through statamic first. This is useful if you want to stop ad-blockers from blocking requests to plausible.
- Option to proxy `plausible.js` to prevent ad-blockers from blocking plausible.

## How to Install

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:

``` bash
composer require digitalwerft/statamic-plausible-proxy
```

## How to Use

In your `head` simply include the following tag:

```
{{ plausible }}
```

This will render a link tag to plausible.

Use the following .env variables to configure the addon:
```env
PLAUSIBLE_SITE="website.com" # url of the website you used to register plausible with
PLAUSIBLE_ENABLED=false # activate or deactivate the addon entirely (eg. in development mode)
PLAUSIBLE_PROXY_ENABLED=true # proxy events through statamic
```

The values are read through `config('plausible-proxy.*')` (see [`config/plausible-proxy.php`](config/plausible-proxy.php)), so the tag keeps working after `php artisan config:cache`. Run `config:cache` again after changing the .env.

## Privacy

With the proxy enabled, the visitor's browser only talks to your site. The addon forwards events to plausible.io with a shortened ip address in `X-Forwarded-For`: IPv4 keeps the first three octets (`203.0.113.77` → `203.0.113.0`), IPv6 the first 48 bits. The full ip never leaves your server. Plausible uses the shortened ip for the country and the daily visitor hash only.

With `PLAUSIBLE_PROXY_ENABLED=false` the script and the events go to plausible.io directly, and Plausible receives the visitor's full ip.

The proxy reads the visitor ip from `CF-Connecting-IP` (Cloudflare) or `$request->ip()`, so `TrustProxies` must be configured when the site sits behind another proxy.
