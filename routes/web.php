<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('application');
})->where('any', '.*');

// simple reuqest debugger
// Route::get('/', function () {
//     $wonde_connector = new \App\Http\Integrations\Wonde\WondeConnector();
//     $wonde_request = new \App\Http\Integrations\Wonde\Requests\GetSchoolRequest('A1930499544');
//     $wonde_connector->debugRequest(
//         function (Saloon\Http\PendingRequest $pendingRequest, GuzzleHttp\Psr7\Request  $psrRequest) {
//             dd($pendingRequest->headers()->all(), $psrRequest);
//         }
//     );
//     $response = $wonde_connector->send($wonde_request);
//     return $response->json();
// });
