<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasusHukumController;

Route::get('/', function () {
    return response()->json([
        'app' => 'API Platform Transparansi Hukum (Sampaimana)',
        'version' => '1.0.0',
        'status' => 'active',
        'environment' => config('app.env'),
    ]);
});

Route::prefix('api')->group(function () {
    Route::apiResource('kasus', KasusHukumController::class)->parameters([
        'kasus' => 'kasusHukum',
    ]);
});
