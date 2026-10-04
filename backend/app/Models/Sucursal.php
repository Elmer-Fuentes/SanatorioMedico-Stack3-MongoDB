<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Sucursal extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'sucursales';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'correo',
        'ciudad',
        'departamento',
        'fecha_apertura',
        'tipo_sucursal',
        'estado',
    ];

    protected $casts = [
        'fecha_apertura' => 'date',
        'estado' => 'boolean',
    ];
}
