# TOTS - Prueba de Ingreso
# Por: Juan David Parroquaino Vargas

Sistema de reserva de espacios. Angular 17 (Para usar PrimeNG) + Laravel 12.

## Quick Start

### Backend (Laravel)

```bash
cd tots_backend
cp .env.example .env
composer install
php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed
php artisan serve
```

Backend: `http://localhost:8000`

### Frontend (Angular)

```bash
cd tots_front
npm install --legacy-peer-deps
npm start
```

Frontend: `http://localhost:4200`

## Credenciales de prueba

**Admin:**
- admin@tots.com / password

**Usuario:**
- user@tots.com / password

O puedes regístrate directamente desde la app (Como usuario user por defecto).

## ¿Qué contiene?

**Features básicos:**
- Sistema de auth con JWT
- Ver espacios disponibles
- Filtrar espacios (tipo, capacidad, fecha)
- Reservar espacios
- Ver mis reservas
- Editar/cancelar reservas
- Panel admin para CRUD de espacios
- Roles admin/usuario

**Desarrollos Extras:**
- Calendario interactivo que muestra disponibilidad por hora
- Click en el calendario para reservar directamente
- Notificaciones toast
- Dark theme con glass morphism
- 44 tests en backend
- Swagger docs
- Campo type con 4 categorías de espacios

## El calendario

El calendario muestra disponibilidad hora por hora (8am-6pm). Para encontrarlo, debes ingresar a `/spaces`, clickear en `Ver Detalles`, luego ingresas a la pestaña `Disponibilidad`, seleccionas una fecha y se listarán los horarios disponibles de ese día.


## Testing

44 tests en el backend:

```bash
cd tots_backend
php artisan test
```

Cubren:
- Auth (registro, login, logout, tokens)
- CRUD de espacios
- CRUD de reservas  
- Validación de overlaps
- Permisos de admin
- Generación de slots disponibles

## Stack

**Backend:**
- Laravel 12, PHP 8.2+
- MySQL (producción), SQLite (tests)
- JWT (tymon/jwt-auth)
- PHPUnit
- Swagger/L5-Swagger

**Frontend:**
- Angular 17 (standalone components)
- TypeScript 5.4
- PrimeNG 17
- Tailwind CSS
- RxJS

## Base de datos

**users:** id, name, email, password, is_admin  
**spaces:** id, name, description, capacity, location, type, image_url, hourly_rate  
**reservations:** id, space_id, user_id, event_name, start_time, end_time, notes

El type es un enum: sala, auditorio, conferencia, taller.

## Seguridad

- JWT tokens (expiran en 60 minutos)
- Passwords con bcrypt
- Validación en backend
- Guards en frontend
- Interceptor agrega token automáticamente
- CORS configurado
- Los usuarios solo ven sus propias reservas

## Lo que me tomó tiempo

**Backend:**
1. Validación para detectar si dos reservas se superponen
2. Endpoint de available-slots - Calcular qué horas están libres
3. Sistema de permisos - Admin vs usuario
4. Los 44 tests - Cubrir todos los casos
5. Campo type - Con enum y validación

**Frontend:**
1. Calendario interactivo - Reserva con un click
2. Guards e interceptors - JWT automático, protección de rutas
3. Validación de fechas - Que end_time sea después de start_time
4. El diseño dark - Glass morphism con transparencias y gradientes
5. Notificaciones - Usé Toast para mejor desempeño

El más complejo fue la versión compatible de PrimeNG, tenía Angular 21 y lo reduje a 17, puesto que en esa versión tenía la versión free.
