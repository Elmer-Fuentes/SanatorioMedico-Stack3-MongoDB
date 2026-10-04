<?php

namespace App\Services;

use App\Models\Sucursal;
use App\Repositories\SucursalRepository;

class SucursalService
{
    public function __construct(
        private SucursalRepository $repository
    ) {
    }

    public function listar(string $buscar = '')
    {
        return $this->repository->obtenerTodos($buscar);
    }

    public function buscar(string $id): Sucursal
    {
        return $this->repository->buscarPorId($id);
    }

    public function agregar(array $datos): Sucursal
    {
        return $this->repository->crear($datos);
    }

    public function editar(string $id, array $datos): Sucursal
    {
        return $this->repository->actualizar($id, $datos);
    }

    public function eliminar(string $id): void
    {
        $this->repository->eliminar($id);
    }
}

