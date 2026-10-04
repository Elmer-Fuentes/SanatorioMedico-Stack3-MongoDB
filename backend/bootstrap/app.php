<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if (!$request->is('api/sucursales', 'api/sucursales/*')) return null;
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return response()->json(['ok' => false, 'mensaje' => 'Los datos proporcionados no son válidos.', 'errors' => $e->errors()], 422);
            }
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException || $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response()->json(['ok' => false, 'mensaje' => 'Sucursal no encontrada.'], 404);
            }
            $status = $e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface ? $e->getStatusCode() : 500;
            return response()->json(['ok' => false, 'mensaje' => $status >= 500 ? 'No fue posible procesar la operación de sucursales.' : 'La solicitud no es válida.'], $status);
        });
    })->create();

