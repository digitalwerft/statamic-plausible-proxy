<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Middleware\VerifyCsrfToken;

// plausible.io proxy 
Route::get("/pio.js", function (Request $request) {
  $response = Http::get('https://plausible.io/js/plausible.js');
  $contents = $response->body();
  $header = ['Content-Type' => 'application/javascript'];
  return response($contents, 200, $header);
});

Route::post("/api/event", function (Request $request) {
  // get payload of request
  $req = request();
  $content = $req->getContent();
  $header = $req->header();
  $userAgent = array_key_exists('user-agent', $header) ? $header['user-agent'] : '';
  $clientIp = isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $req->ip();

  $headerEvent = [
    "User-Agent"      => $userAgent,
    "X-Forwarded-For" => $clientIp,
    "Content-Type" => "application/json"
  ];
  // send event to plausible
  $response = Http::withHeaders($headerEvent)->send('POST', 'https://plausible.io/api/event', [
    'body' => $content
  ]);

  $headerRes = [
    "Cache-Control"   => "must-revalidate, max-age=0, private"
  ];
  // respond only with status code of plausible
  return response()->json(null, $response->status(), $headerRes);
})->withoutMiddleware([VerifyCsrfToken::class]);
