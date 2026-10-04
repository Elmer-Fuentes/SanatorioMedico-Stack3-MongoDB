<?php

namespace App\Http\Controllers;

use App\Http\Requests\SucursalRequest;
use App\Services\SucursalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function __construct(private SucursalService $service) {}

    public function index(Request $request): JsonResponse
    {
        $datos = $request->validate(['buscar' => 'nullable|string|max:150']);
        return response()->json([
            'ok' => true,
            'mensaje' => 'Sucursales consultadas correctamente.',
            'datos' => $this->service->listar(trim($datos['buscar'] ?? '')),
        ]);
    }

    public function store(SucursalRequest $request): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'mensaje' => 'Sucursal creada correctamente.',
            'datos' => $this->service->agregar($request->validated()),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'mensaje' => 'Sucursal consultada correctamente.',
            'datos' => $this->service->buscar($id),
        ]);
    }

    public function update(SucursalRequest $request, string $id): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'mensaje' => 'Sucursal actualizada correctamente.',
            'datos' => $this->service->editar($id, $request->validated()),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->eliminar($id);
        return response()->json([
            'ok' => true,
            'mensaje' => 'Sucursal eliminada correctamente.',
            'datos' => null,
        ]);
    }
}
