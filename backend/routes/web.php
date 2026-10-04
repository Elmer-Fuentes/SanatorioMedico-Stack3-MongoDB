<?php

use Illuminate\Support\Facades\Route;

Route::view('/api/documentation', 'swagger')->name('swagger.ui');
Route::get('/api/openapi.json', function () {
    $spec = json_decode(file_get_contents(resource_path('openapi.json')), true, 512, JSON_THROW_ON_ERROR);
    $spec['servers'] = [['url' => url('/api')]];

    return response()->json($spec);
})->name('swagger.spec');

Route::get('/', function () {
    return response()->json([
        'aplicacion' => 'Sanatorio Médico - Stack 3',
        'api' => '/api/ping',
    ]);
});
