
# MongoDB — Docker Desktop en Windows

## 1. Configuración del entorno

- Sistema operativo: Windows 11
- Docker Desktop: ejecución de MongoDB
- MongoDB: versión 8.2
- MongoDB Compass: administración gráfica
- Laravel: backend API
- Vue.js + Vite: frontend

Datos de conexión:

| Parámetro | Valor |
|---|---|
| Contenedor | mongodb_desarrolloweb |
| Imagen | mongo:8.2 |
| Servidor | 127.0.0.1 |
| Puerto | 27017 |
| Usuario administrador | admin |
| Base de datos de autenticación | admin |
| Base de datos del proyecto | db_SanatorioMedico |

La contraseña de MongoDB se configura durante la creación del contenedor. No debe publicarse en GitHub.

## 2. Crear el contenedor MongoDB

Este paso se ejecuta solamente la primera vez.

Abrir PowerShell o CMD en Windows y ejecutar:

```cmd
docker run -d --name mongodb_desarrolloweb --restart unless-stopped -p 27017:27017 -e MONGO_INITDB_ROOT_USERNAME=admin -e "MONGO_INITDB_ROOT_PASSWORD=Desarrolloweb_2026*" -e MONGO_INITDB_DATABASE=db_SanatorioMedico -v mongodb_desarrolloweb_data:/data/db mongo:8.2
```

Este comando crea el contenedor y un volumen persistente.

**Importante:** la contraseña mostrada es únicamente para el laboratorio local. Para proyectos reales deben utilizarse credenciales propias y seguras.

## 3. Iniciar MongoDB

Primero abrir Docker Desktop.

Iniciar el contenedor existente:

```cmd
docker start mongodb_desarrolloweb
```

Verificar que esté funcionando:

```cmd
docker ps
```

Mostrar todos los contenedores:

```cmd
docker ps -a
```

Verificar los registros:

```cmd
docker logs --tail 30 mongodb_desarrolloweb
```

Detener MongoDB cuando sea necesario:

```cmd
docker stop mongodb_desarrolloweb
```

No ejecutar nuevamente `docker run` si el contenedor ya existe.

## 4. Conectar MongoDB Compass

Abrir MongoDB Compass en Windows.

Crear una conexión con estos parámetros:

- Host: 127.0.0.1
- Puerto: 27017
- Usuario: admin
- Contraseña: la definida al crear el contenedor
- Authentication Database: admin

Cadena de conexión para el laboratorio:

```text
mongodb://admin:Desarrolloweb_2026%2A@127.0.0.1:27017/?authSource=admin
```

Seleccionar Connect.

## 5. Configurar Laravel

Abrir el archivo:

`backend/.env`

Configurar:

```dotenv
DB_CONNECTION=mongodb
DB_DATABASE=db_SanatorioMedico
DB_URI="mongodb://admin:Desarrolloweb_2026%2A@127.0.0.1:27017/?authSource=admin"
```

El símbolo `*` de la contraseña se representa como `%2A` dentro de la URI.

El archivo `.env` no debe publicarse en GitHub.

## 6. Verificar configuración Laravel

Abrir CMD en la carpeta backend:

```cmd
cd C:\2026_DesarrolloWeb\SanatorioMedicoStack3\backend
```

Limpiar configuración:

```cmd
php artisan config:clear
```

Verificar la configuración sin mostrar la contraseña:

```cmd
php artisan tinker
```

Dentro de Tinker:

```php
config('database.default');
```

Resultado esperado:

```text
mongodb
```

## 7. Probar conexión Laravel → MongoDB

Dentro de Tinker:

```php
DB::connection('mongodb')->getMongoDB()->command(['ping' => 1])->toArray();
```

Resultado esperado: un documento que contenga `ok: 1`.

Salir de Tinker:

```php
exit
```

## 8. Ejecutar Backend Laravel

Desde CMD:

```cmd
cd C:\2026_DesarrolloWeb\SanatorioMedicoStack3\backend
php artisan serve
```

Direcciones:

- http://127.0.0.1:8000/api/ping
- http://127.0.0.1:8000/api/sucursales

## 9. Ejecutar Frontend Vue.js

Abrir una segunda terminal CMD:

```cmd
cd C:\2026_DesarrolloWeb\SanatorioMedicoStack3\frontend
npm run dev
```

Abrir:

http://localhost:5173/

## 10. Consultar rutas API

Desde backend:

```cmd
php artisan route:list --path=api
```

## 11. Solución de problemas

### MongoDB no conecta

1. Comprobar que Docker Desktop esté abierto.
2. Ejecutar `docker ps`.
3. Confirmar que el contenedor esté funcionando.
4. Confirmar que el puerto 27017 esté disponible.
5. Verificar usuario, contraseña y `authSource=admin`.

### Laravel no reconoce cambios del .env

```cmd
php artisan config:clear
php artisan optimize:clear
```

### Vue.js no inicia

Verificar que la terminal esté dentro de `frontend`.

```cmd
npm install
npm run dev
```

### El navegador no muestra los datos

Comprobar que estén funcionando los tres componentes:

- MongoDB en Docker Desktop.
- Backend Laravel en puerto 8000.
- Frontend Vue.js en puerto 5173.

## 12. Observaciones

- Docker Desktop debe ejecutarse en Windows.
- MongoDB Compass también se ejecuta en Windows.
- No se necesita conocer la IP del equipo para trabajar localmente.
- Utilizar 127.0.0.1 para conectar Laravel con MongoDB.
- El nombre de la base de datos debe ser db_SanatorioMedico.
- La base de datos puede no aparecer en Compass hasta que tenga al menos una colección con datos.
- No compartir credenciales reales ni publicar archivos .env.
