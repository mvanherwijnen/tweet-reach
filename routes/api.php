<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/tweet/{id}/{relation?}', function () {})
    ->where('id', '[0-9]+')
    ->middleware('resource.group')
    ->name('tweet');

Route::get('/redis', function () {
    $visits = Redis::Incr('visits');
    return $visits;
});
