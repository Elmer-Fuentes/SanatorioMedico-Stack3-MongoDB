<?php

use App\Http\Controllers\SucursalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function (Request $request) {
    try {
        DB::connection('mongodb')->command(['ping' => 1]);

        return response()->json([
            'ok' => true,
            'mensaje' => 'MongoDB conectado correctamente',
        ]);
    } catch (\Throwable $e) {
        report($e);

        return response()->json([
            'ok' => false,
            'mensaje' => 'No fue posible conectar con MongoDB',
            'error' => config('app.debug') ? $e->getMessage() : null,
        ], 500);
    }
});

Route::get('/sucursales', [SucursalController::class, 'index']);
Route::post('/sucursales', [SucursalController::class, 'store']);
Route::get('/sucursales/{id}', [SucursalController::class, 'show']);
Route::put('/sucursales/{id}', [SucursalController::class, 'update']);
Route::delete('/sucursales/{id}', [SucursalController::class, 'destroy']);
