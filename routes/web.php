<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Http\Middleware\VerifyCsrfToken;

// plausible.io proxy, cache for 1 day 
Route::get("/pio.js", function (Request $request) {
  // Define a unique cache key for the script
  $cacheKey = 'plausible_js_script';

  // Try to get the cached content
  $contents = Cache::remember($cacheKey, 86400, function () { // Cache for 1 day (86400 seconds)
      $response = Http::get('https://plausible.io/js/script.js');

      if ($response->successful()) {
          return $response->body(); // Cache the response body
      }

      abort(502, 'Unable to fetch the script from the third-party provider.');
  });

  // Return the response with appropriate headers
  return response($contents, 200, ['Content-Type' => 'application/javascript']);
});

Route::post("/api/event", function (Request $request) {
  // get payload of request
  $req = request();
  $content = $req->getContent();
  $header = $req->header();
  $userAgent = array_key_exists('user-agent', $header) ? $header['user-agent'] : '';
  $clientIp = isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $req->ip();
  $maskedIp = preg_replace('/\.\d+$/', '.1', $clientIp);

  $headerEvent = [
    "User-Agent"      => $userAgent,
    "X-Forwarded-For" => $maskedIp,
    "Content-Type" => "application/json"
  ];
  // send event to plausible, timeout of 10s

  $response = Http::withHeaders($headerEvent)->send('POST', 'https://plausible.io/api/event', [
    'body' => $content
  ]);

  $headerRes = [
    "Cache-Control"   => "must-revalidate, max-age=0, private"
  ];
  // respond only with status code of plausible
  return response()->json(null, $response->status(), $headerRes);
})->withoutMiddleware([VerifyCsrfToken::class]);
