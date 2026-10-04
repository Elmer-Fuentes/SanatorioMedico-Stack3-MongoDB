<?php

// Prueba contra el MongoDB configurado. Solo elimina el documento que crea.
require __DIR__.'/../../backend/vendor/autoload.php';
$app = require __DIR__.'/../../backend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$id = null;

function consultar(string $metodo, string $url, ?array $datos = null): array
{
    global $kernel;
    $request = Illuminate\Http\Request::create($url, $metodo, [], [], [], [
        'HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json',
    ], $datos === null ? null : json_encode($datos));
    $response = $kernel->handle($request);
    $kernel->terminate($request, $response);
    return [$response->getStatusCode(), json_decode($response->getContent(), true)];
}

function comprobar(bool $condicion, string $mensaje): void
{
    if (!$condicion) throw new RuntimeException($mensaje);
    echo "OK: $mensaje\n";
}

try {
    [$status, $body] = consultar('GET', '/api/sucursales');
    comprobar($status === 200 && $body['ok'] && is_array($body['datos']), 'Listar');
    [$status, $body] = consultar('POST', '/api/sucursales', []);
    comprobar($status === 422 && !$body['ok'] && isset($body['errors']['nombre']), 'Validación JSON 422');
    $marca = 'PruebaSucursal'.bin2hex(random_bytes(6));
    $datos = ['nombre' => $marca, 'direccion' => 'Dirección de prueba', 'telefono' => '5555-1234',
        'correo' => 'prueba@example.com', 'ciudad' => 'CiudadPrueba', 'departamento' => 'DepartamentoPrueba',
        'fecha_apertura' => '2026-01-15', 'tipo_sucursal' => 'Principal', 'estado' => true];
    [$status, $body] = consultar('POST', '/api/sucursales', $datos);
    $id = $body['datos']['id'] ?? $body['datos']['_id'] ?? null;
    comprobar($status === 201 && $body['ok'] && is_string($id) && preg_match('/^[a-f0-9]{24}$/i', $id), 'Crear con ID de MongoDB');
    [$status, $body] = consultar('GET', '/api/sucursales/'.$id);
    comprobar($status === 200 && $body['datos']['nombre'] === $marca, 'Buscar por ID');
    foreach (['nombre', 'ciudad', 'departamento', 'telefono', 'correo', 'tipo_sucursal'] as $campo) {
        $texto = strtolower(substr($datos[$campo], 1, -1));
        [$status, $body] = consultar('GET', '/api/sucursales?buscar='.urlencode($texto));
        comprobar($status === 200 && count(array_filter($body['datos'], fn ($s) => ($s['id'] ?? $s['_id'] ?? '') === $id)) === 1, 'Búsqueda parcial: '.$campo);
    }
    [$status, $body] = consultar('GET', '/api/sucursales?buscar='.urlencode($marca.'.*'));
    comprobar($status === 200 && count($body['datos']) === 0, 'Búsqueda literal sin inyección regex');
    $datos['nombre'] .= ' editada';
    $datos['estado'] = false;
    [$status, $body] = consultar('PUT', '/api/sucursales/'.$id, $datos);
    comprobar($status === 200 && $body['ok'] && $body['datos']['nombre'] === $datos['nombre'] && $body['datos']['estado'] === false, 'Editar y mantener estado false');
    foreach (['undefined', '000000000000000000000000'] as $inexistente) {
        foreach (['GET', 'PUT', 'DELETE'] as $metodo) {
            [$status, $body] = consultar($metodo, '/api/sucursales/'.$inexistente, $metodo === 'PUT' ? $datos : null);
            comprobar($status === 404 && !$body['ok'] && !isset($body['exception']), "$metodo ID inválido o inexistente: $inexistente");
        }
    }
    [$status, $body] = consultar('GET', '/api/sucursales?buscar[]=incorrecto');
    comprobar($status === 422 && !$body['ok'], 'Validar parámetro de búsqueda');
    [$status, $body] = consultar('DELETE', '/api/sucursales/'.$id);
    comprobar($status === 200 && $body['ok'], 'Eliminar');
    [$status, $body] = consultar('GET', '/api/sucursales/'.$id);
    comprobar($status === 404 && !$body['ok'], 'Confirmar eliminación');
} finally {
    if ($id) consultar('DELETE', '/api/sucursales/'.$id);
}
