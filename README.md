# Sanatorio Médico — Stack 3 FULL STACK

Proyecto construido de acuerdo con los documentos entregados.

## Stack

- Base de datos: MongoDB Community 8.2
- Contenedores: Docker Desktop
- Backend: Laravel 13 / PHP 8.3–8.5
- Integración MongoDB: `mongodb/laravel-mongodb:^5.11`
- Frontend: Vue 3
- Tooling: Vite
- API: REST / JSON
- Cliente HTTP: Axios
- Pruebas API: Postman

## Módulo implementado

CRUD completo de **Sucursales**:

- Consultar todas
- Crear
- Consultar por `_id`
- Editar
- Eliminar

Campos:

- `nombre`
- `direccion`
- `telefono`
- `correo`
- `ciudad`
- `departamento`
- `fecha_apertura`
- `tipo_sucursal`
- `estado`

Arquitectura Backend:

`Route -> Controller -> FormRequest -> Service -> Repository -> Model -> MongoDB`

Flujo Frontend:

`Vue -> Axios -> Laravel REST API -> MongoDB`

---

# 1. Requisitos en Windows

Abrir Visual Studio Code como administrador y validar:

```bat
php -v
composer --version
node -v
npm -v
git --version
docker --version
docker compose version
```

PHP debe tener activa la extensión MongoDB:

```bat
php -m | findstr /I "mongodb"
php --ri mongodb
```

También son necesarias las extensiones:

```bat
php -m | findstr /I "curl fileinfo mbstring openssl"
```

Si no existe la extensión MongoDB, seguir el procedimiento indicado por el curso con PIE:

```bat
curl -L https://github.com/php/pie/releases/latest/download/pie.phar -o pie.phar
php pie.phar install mongodb/mongodb-extension
php -m | findstr /I "mongodb"
```

> PHP 8.5 es válido para este proyecto. Laravel 13 requiere PHP 8.3 o superior.

---

# 2. Configuración de MongoDB

La contraseña solicitada ya está configurada:

- Usuario: `admin`
- Contraseña: `Desarrolloweb_2026*`
- Base: `sanatorio_medico`
- Host: `localhost`
- Puerto: `27017`
- Authentication DB: `admin`

Cadena de conexión:

```text
mongodb://admin:Desarrolloweb_2026*@localhost:27017/?authSource=admin
```

Levantar MongoDB:

```bat
docker compose config
docker compose up -d
docker ps
```

MongoDB Compass:

```text
mongodb://admin:Desarrolloweb_2026*@localhost:27017/?authSource=admin
```

---

# 3. Instalación rápida recomendada

Desde la raíz del proyecto, en PowerShell:

```powershell
Set-ExecutionPolicy -Scope Process Bypass
.\scripts\setup.ps1
```

El script:

1. Verifica herramientas.
2. Verifica la extensión MongoDB de PHP.
3. Levanta MongoDB.
4. Ejecuta `composer install`.
5. Verifica `.env`.
6. Ejecuta `php artisan optimize:clear`.
7. Muestra las rutas API.
8. Ejecuta `npm install` en Vue.

Después:

```powershell
.\scripts\start.ps1
```

Esto abre dos terminales:

- Laravel: `http://127.0.0.1:8000`
- Vue: `http://localhost:5173`

---

# 4. Instalación manual

## Backend

```bat
cd backend
composer install
php artisan optimize:clear
php artisan route:list --path=api
php artisan serve
```

Prueba:

```text
GET http://127.0.0.1:8000/api/ping
```

Resultado esperado:

```json
{
  "ok": true,
  "mensaje": "MongoDB conectado correctamente"
}
```

## Frontend

En otra terminal:

```bat
cd frontend
npm install
npm run dev
```

Abrir:

```text
http://localhost:5173
```

---

# 5. Endpoints

| Método | Endpoint | Acción |
|---|---|---|
| GET | `/api/ping` | Probar conexión MongoDB |
| GET | `/api/sucursales` | Listar |
| POST | `/api/sucursales` | Crear |
| GET | `/api/sucursales/{id}` | Buscar |
| PUT | `/api/sucursales/{id}` | Editar |
| DELETE | `/api/sucursales/{id}` | Eliminar |

Ejemplo POST:

```json
{
  "nombre": "Sucursal Central",
  "direccion": "Zona 10, Ciudad de Guatemala",
  "telefono": "2222-1000",
  "correo": "central@sanatorio.gt",
  "ciudad": "Guatemala",
  "departamento": "Guatemala",
  "fecha_apertura": "2026-10-04",
  "tipo_sucursal": "Principal",
  "estado": true
}
```

---

# 6. Postman

Importar:

```text
postman/SanatorioMedicoStack3.postman_collection.json
```

La colección incluye Ping + CRUD completo.

---

# 7. Datos de prueba opcionales

Con MongoDB ya levantado:

```powershell
.\scripts\seed.ps1
```

Esto carga registros de demostración desde:

```text
database/seed_sucursales.js
```

---

# 8. Si cambiaste una contraseña en un volumen MongoDB existente

Las variables `MONGO_INITDB_ROOT_*` solamente se aplican cuando MongoDB inicializa un volumen nuevo.

Para reiniciar la base desde cero:

```powershell
.\scripts\reset-db.ps1
```

**Advertencia:** elimina el volumen de desarrollo y todos sus datos.

---

# 9. Estructura

```text
SanatorioMedicoStack3/
├── backend/
│   ├── app/
│   │   ├── Http/
│   │   ├── Models/
│   │   ├── Repositories/
│   │   └── Services/
│   ├── config/
│   ├── routes/
│   └── .env
├── frontend/
│   ├── src/
│   │   ├── components/sucursales/
│   │   ├── services/
│   │   └── views/
│   └── .env
├── database/
├── postman/
├── scripts/
├── ia/
└── docker-compose.yml
```

## Nota sobre dependencias

`vendor/` y `node_modules/` no se incluyen en el ZIP porque son dependencias generadas. Se instalan de forma reproducible con:

```bat
composer install
npm install
```

El proyecto sí contiene todos los archivos fuente y configuración necesarios.
