# API de Reserva de Espacios para Eventos

Una API REST construida con **Laravel 12** para gestionar la reserva de espacios (salas de reuniones, auditorios, etc.) para eventos.

## Características

- ✅ Autenticación JWT
- ✅ CRUD completo para espacios y reservas
- ✅ Validación automática de conflictos horarios
- ✅ Sistema de roles (Admin/Usuario)
- ✅ Suite de tests automáticos
- ✅ Generador de slots disponibles por fecha

## Stack

- Laravel 12
- PHP 8.2+
- MySQL/SQLite
- JWT Auth
- PHPUnit

## Quick Start

```bash
# 1. Instalar
cd tots_backend
composer install

# 2. Configurar
cp .env.example .env
php artisan key:generate

# 3. Base de datos
php artisan migrate --seed

# 4. Correr
php artisan serve
```

Disponible en `http://localhost:8000`

## 📚 Documentación API (Swagger)

La documentación interactiva de la API está disponible en:

**URL**: `http://localhost:8000/api/documentation`

Para regenerar la documentación después de cambios:
```bash
php artisan l5-swagger:generate
```

## API Endpoints

### Autenticación
- `POST /api/auth/register` - Registrar nuevo usuario
- `POST /api/auth/login` - Login y recibir token
- `POST /api/auth/logout` - Logout (requiere token)
- `GET /api/auth/me` - Ver usuario actual
- `POST /api/auth/refresh` - Refrescar token

### Espacios
- `GET /api/spaces` - Listar todos con filtros
  - Parámetros: `min_capacity`, `max_capacity`, `search`
- `GET /api/spaces/{id}` - Ver detalle
- `POST /api/spaces` - Crear (Admin)
- `PUT /api/spaces/{id}` - Actualizar (Admin)
- `DELETE /api/spaces/{id}` - Eliminar (Admin)

### Reservas
- `GET /api/reservations` - Ver mis reservas
- `GET /api/reservations/{id}` - Ver detalle
- `POST /api/reservations` - Crear reserva
- `PUT /api/reservations/{id}` - Modificar reserva
- `DELETE /api/reservations/{id}` - Cancelar reserva
- `GET /api/reservations/available-slots` - Slots libres
  - Parámetros: `space_id`, `date` (Y-m-d)

## Base de Datos

**Usuarios**
- id, name, email, password, is_admin

**Espacios**
- id, name, description, capacity, location, image_url, hourly_rate

**Reservas**
- id, space_id, user_id, event_name, start_time, end_time, notes

## Seeders

Ejecutar `php artisan migrate --seed` crea:
- 1 admin: `admin@example.com`
- 5 usuarios de prueba
- 5 espacios de ejemplo

## Testing

```bash
php artisan test
```

Pruebas incluidas:
- Autenticación (registro, login, validaciones)
- Espacios (CRUD con permisos)
- Reservas (crear, modificar, eliminar, conflictos horarios)

## Ejemplo de Uso

```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'

# Crear reserva (con token obtenido)
curl -X POST http://localhost:8000/api/reservations \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "space_id": 1,
    "event_name": "Team Meeting",
    "start_time": "2024-12-20 14:00:00",
    "end_time": "2024-12-20 16:00:00",
    "notes": "Reunión importante"
  }'

# Ver slots disponibles
curl -X GET "http://localhost:8000/api/reservations/available-slots?space_id=1&date=2024-12-20" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Lo Más Destacado

1. **Validación de conflictos robusta**: Detecta cualquier solapamiento de horarios
2. **Sistema de roles claro**: Admin vs usuario normal
3. **Tests completos**: Cobertura de casos principales y edge cases
4. **API RESTful**: Endpoints coherentes y bien estructurados
5. **Seeders realistas**: Base de datos pre-cargada con espacios útiles

## Notas Importantes

- Todos los endpoints excepto `/api/auth/register` y `/api/auth/login` requieren token JWT
- Los tokens expiran en 24 horas
- Solo admin puede crear/editar/eliminar espacios
- Cada usuario solo ve/edita sus propias reservas
- Los conflictos horarios se previenen automáticamente
