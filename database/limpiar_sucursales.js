const dbSanatorio = db.getSiblingDB('sanatorio_medico');
dbSanatorio.sucursales.deleteMany({});
print('Colección sucursales limpiada.');
