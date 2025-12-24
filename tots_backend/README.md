# Backend API - TOTS

API REST con Laravel 12 para gestionar reservas de espacios.

## Lo que hace

Es el backend del sistema de reservas. Maneja usuarios, espacios y reservas. Tiene autenticación con JWT, valida que las reservas no se repitan, y respeta los permisos de admin vs usuario normal.

## Stack

- Laravel 12
- PHP 8.2+
- MySQL (desarrollo y producción)
- SQLite (para tests)
- JWT para auth (tymon/jwt-auth)
- PHPUnit para testing
- Swagger/L5-Swagger para docs

## Instalación

```bash
cd tots_backend

# 1. Copiar el archivo de entorno, editarlo según corresponda
cp .env.example .env

# 2. Instalar dependencias
composer install

# 3. Generar las keys
php artisan key:generate
php artisan jwt:secret

# 4. Configurar la base de datos en .env
# DB_DATABASE=tots_db
# DB_USERNAME=root
# DB_PASSWORD=tu_password

# 5. Crear la base de datos (si no existe)
# mysql -u root -p
# CREATE DATABASE tots_db;
# exit;

# 6. Correr migraciones
php artisan migrate

# 7. Seed con datos de prueba
php artisan db:seed

# 8. Levantar el servidor
php artisan serve
```

La API estará disponible en `http://localhost:8000/api`

## Autenticación

Uso JWT. Básicamente:

1. Usuario se registra o loguea
2. El Backend devuelve un token JWT
3. El Frontend guarda el token
4. Todos los requests envia automáticamente en el header el `Authorization: Bearer {token}`
5. El token expira en 60 minutos

## Endpoints

### Auth

| Método | Ruta | Descripción | Auth | Admin |
|--------|------|-------------|------|-------|
| POST | `/api/auth/register` | Crear cuenta | No | No |
| POST | `/api/auth/login` | Obtener token | No | No |
| POST | `/api/auth/logout` | Invalidar token | Sí | No |
| GET | `/api/auth/me` | Info del usuario actual | Sí | No |

### Spaces

| Método | Ruta | Descripción | Auth | Admin |
|--------|------|-------------|------|-------|
| GET | `/api/spaces` | Listar espacios (con filtros) | Sí | No |
| POST | `/api/spaces` | Crear espacio | Sí | Sí |
| GET | `/api/spaces/{id}` | Ver un espacio | Sí | No |
| PUT | `/api/spaces/{id}` | Actualizar espacio | Sí | Sí |
| DELETE | `/api/spaces/{id}` | Eliminar espacio | Sí | Sí |
| GET | `/api/spaces/{id}/available-slots` | Slots disponibles por fecha | Sí | No |

**Filtros para GET `/api/spaces`:**
- `search` - Buscar por nombre
- `type` - Filtrar por tipo (sala, auditorio, conferencia, taller)
- `min_capacity` - Capacidad mínima
- `max_capacity` - Capacidad máxima
- `date` - Ver solo los disponibles en esta fecha

**Ejemplo:**
```
GET /api/spaces?type=sala&min_capacity=10&date=2025-12-25
```

### Reservations

| Método | Ruta | Descripción | Auth | Admin |
|--------|------|-------------|------|-------|
| GET | `/api/reservations` | Mis reservas | Sí | No |
| POST | `/api/reservations` | Crear reserva | Sí | No |
| GET | `/api/reservations/{id}` | Ver una reserva | Sí | No |
| PUT | `/api/reservations/{id}` | Actualizar reserva | Sí | No |
| DELETE | `/api/reservations/{id}` | Cancelar reserva | Sí | No |

Los usuarios solo pueden ver/editar/eliminar sus propias reservas. Si intentas reservar un horario ocupado, te devuelve error 422.

## Testing

```bash
php artisan test
```

## Swagger Documentation

La API está documentada con Swagger. Para verla:

```bash
php artisan l5-swagger:generate
```

Luego ve a: `http://localhost:8000/api/documentation`

Ahí puedes probar todos los endpoints desde el navegador.
