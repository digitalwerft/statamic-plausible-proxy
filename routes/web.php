<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Http\Middleware\VerifyCsrfToken;
use Digitalwerft\PlausibleProxy\IpAnonymizer;

// plausible.io proxy, cache script for 1 day
Route::get("/pio.js", function () {
    $contents = Cache::remember('plausible_js_script', 86400, function () {
        $response = Http::get('https://plausible.io/js/script.js');

        if ($response->successful()) {
            return $response->body();
        }

        abort(502, 'Unable to fetch the script from the third-party provider.');
    });

    return response($contents, 200, [
        'Content-Type'  => 'application/javascript',
        'Cache-Control' => 'public, max-age=86400, must-revalidate',
    ]);
});

// Forward events to plausible.
Route::post("/api/event", function (Request $request) {
    // Determine the real visitor ip and shorten it (IPv4 /24, IPv6 /48):
    // the full ip must not be passed to a third party without consent.
    // Requires TrustProxies to be configured so $request->ip() returns the client.
    $clientIp = $request->header('CF-Connecting-IP') ?: $request->ip();
    $maskedIp = IpAnonymizer::anonymize($clientIp);

    // Pass through the content type the script actually used (v34 sends text/plain).
    $contentType = $request->header('Content-Type', 'text/plain');

    // Without a valid ip no X-Forwarded-For is sent; Plausible then sees only the server ip.
    $response = Http::withHeaders(array_filter([
            'User-Agent'      => $request->userAgent() ?? '',
            'X-Forwarded-For' => $maskedIp,
        ], fn ($value) => $value !== null))
        ->withBody($request->getContent(), $contentType)
        ->post('https://plausible.io/api/event');

    // Mirror plausible's status and surface the dropped header so issues are visible.
    return response($response->body(), $response->status())
        ->header('Cache-Control', 'no-store, max-age=0')
        ->header('x-plausible-dropped', $response->header('x-plausible-dropped') ?: '0');
})->withoutMiddleware([VerifyCsrfToken::class]);
