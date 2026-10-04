<?php

namespace App\Repositories;

use App\Models\Sucursal;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SucursalRepository
{
    public function obtenerTodos(string $buscar = '')
    {
        $query = Sucursal::query();
        if ($buscar !== '') {
            $regex = new \MongoDB\BSON\Regex(preg_quote($buscar), 'i');
            $query->where(function ($query) use ($regex) {
                foreach (['nombre', 'ciudad', 'departamento', 'telefono', 'correo', 'tipo_sucursal'] as $campo) {
                    $query->orWhere($campo, 'regex', $regex);
                }
            });
        }
        return $query->orderBy('nombre')->get();
    }

    public function buscarPorId(string $id): Sucursal
    {
        $this->validarId($id);

        /** @var Sucursal|null $sucursal */
        $sucursal = Sucursal::find($id);

        if (!$sucursal) {
            throw (new ModelNotFoundException())->setModel(Sucursal::class, [$id]);
        }

        return $sucursal;
    }

    public function crear(array $datos): Sucursal
    {
        return Sucursal::create($datos);
    }

    public function actualizar(string $id, array $datos): Sucursal
    {
        $sucursal = $this->buscarPorId($id);
        $sucursal->update($datos);

        return $sucursal->fresh();
    }

    public function eliminar(string $id): void
    {
        $sucursal = $this->buscarPorId($id);
        $sucursal->delete();
    }

    private function validarId(string $id): void
    {
        if (!preg_match('/^[a-f0-9]{24}$/i', $id)) {
            throw (new ModelNotFoundException())->setModel(Sucursal::class, [$id]);
        }
    }
}

