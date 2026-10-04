const dbSanatorio = db.getSiblingDB('sanatorio_medico');

dbSanatorio.sucursales.deleteMany({
  correo: {
    $in: [
      'central@sanatorio.gt',
      'sur@sanatorio.gt'
    ]
  }
});

dbSanatorio.sucursales.insertMany([
  {
    nombre: 'Sucursal Central',
    direccion: 'Zona 10, Ciudad de Guatemala',
    telefono: '2222-1000',
    correo: 'central@sanatorio.gt',
    ciudad: 'Guatemala',
    departamento: 'Guatemala',
    fecha_apertura: new Date('2026-10-04T00:00:00Z'),
    tipo_sucursal: 'Principal',
    estado: true,
    created_at: new Date(),
    updated_at: new Date()
  },
  {
    nombre: 'Sucursal Sur',
    direccion: 'Villa Nueva, Guatemala',
    telefono: '2222-2000',
    correo: 'sur@sanatorio.gt',
    ciudad: 'Villa Nueva',
    departamento: 'Guatemala',
    fecha_apertura: new Date('2026-10-04T00:00:00Z'),
    tipo_sucursal: 'Secundaria',
    estado: true,
    created_at: new Date(),
    updated_at: new Date()
  }
]);

dbSanatorio.sucursales.createIndex({ nombre: 1 });
dbSanatorio.sucursales.createIndex({ ciudad: 1 });
dbSanatorio.sucursales.createIndex({ estado: 1 });

print('Datos de prueba cargados en sanatorio_medico.sucursales');
