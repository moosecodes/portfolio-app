<?php

use App\Models\WeatherReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/weather/{zip}', function(string $zip) {
    $response = Http::get(env('WEATHER_API_URL'), [
        'key' => env('WEATHER_API_KEY'),
        'q' => $zip
    ]);
    $reading = $response->json();

    $weatherReading = new WeatherReading;

    $weatherReading->temp_f = $reading['current']['temp_f'];
    $weatherReading->temp_c = $reading['current']['temp_c'];
    $weatherReading->city = $reading['location']['name'];
    $weatherReading->region = $reading['location']['region'];
    $weatherReading->save();

    return $weatherReading->orderBy('id', 'DESC')->first();
});
