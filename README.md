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
