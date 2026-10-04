<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SwaggerTest extends TestCase
{
    public function test_documentation_and_specification_are_available(): void
    {
        $this->get('/api/documentation')->assertOk()->assertSee('SwaggerUIBundle', false);
        $spec = $this->getJson('/api/openapi.json')->assertOk()
            ->assertJsonPath('openapi', '3.0.3')->json();

        foreach (Route::getRoutes() as $route) {
            if (!str_starts_with($route->uri(), 'api/') || str_starts_with($route->getName() ?? '', 'swagger.')) {
                continue;
            }

            $path = '/'.substr($route->uri(), 4);
            foreach (array_diff($route->methods(), ['HEAD']) as $method) {
                $this->assertArrayHasKey(strtolower($method), $spec['paths'][$path] ?? [], "Missing documentation: {$method} {$path}");
            }
        }
    }

    public function test_invalid_sucursal_payload_returns_documented_validation_errors(): void
    {
        $this->postJson('/api/sucursales', [])->assertUnprocessable()
            ->assertJsonValidationErrors([
                'nombre', 'direccion', 'telefono', 'correo', 'ciudad',
                'departamento', 'fecha_apertura', 'tipo_sucursal', 'estado',
            ]);
    }
}
