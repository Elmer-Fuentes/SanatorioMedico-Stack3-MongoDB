# Sanatorio Médico — Backend

## Probar las APIs con Swagger

Con PHP 8.3 o superior, las extensiones requeridas por Composer y MongoDB configurado en `.env`:

```sh
composer install
php artisan serve
```

Abre http://localhost:8000/api/documentation. Selecciona una operación, pulsa **Try it out**, completa los datos y pulsa **Execute** para ver el código HTTP y la respuesta real.

- `GET /api/ping`: comprueba la conexión con MongoDB.
- `GET /api/sucursales`: lista las sucursales.
- `POST /api/sucursales`: crea una sucursal con el JSON de ejemplo.
- `GET /api/sucursales/{id}`: consulta una sucursal.
- `PUT /api/sucursales/{id}`: actualiza una sucursal; requiere todos los campos.
- `DELETE /api/sucursales/{id}`: elimina una sucursal.

Usa el `id` devuelto al crear o listar sucursales. Un cuerpo incompleto devuelve `422`; un identificador inválido o inexistente devuelve `404`. Las ejecuciones modifican la base configurada.

La especificación está en `resources/openapi.json` y se publica en `/api/openapi.json`. Actualízala al cambiar rutas, validaciones o respuestas. La URL del servidor se ajusta al backend que sirve la documentación.

Swagger UI carga sus recursos desde unpkg (versión fija 5.31.0), por lo que el navegador requiere acceso a internet. La documentación es pública, igual que las APIs actuales. Integración basada en la [documentación oficial de Swagger UI](https://swagger.io/docs/open-source-tools/swagger-ui/usage/installation/).

Pruebas de documentación, cobertura de rutas y validación de campos, sin conexión a MongoDB:

```sh
php artisan test --filter=SwaggerTest
```
