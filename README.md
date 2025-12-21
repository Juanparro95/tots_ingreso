# Aplicación de Reserva de Espacios para Eventos

Una aplicación full-stack moderna para reservar espacios (salas de reuniones, auditorios, etc.) para eventos. Construida con **Angular 21** en el frontend y **Laravel 12** en el backend.

## 🎯 Proyecto Completo

Este proyecto incluye dos aplicaciones independientes que se comunican mediante una API REST:

### Frontend (Angular 21)
- **Ubicación**: `tots_front/`
- **Tipo**: SPA (Single Page Application)
- **Features**: UI moderna, responsive, autenticación JWT
- [Ver README Frontend](tots_front/README.md)

### Backend (Laravel 12)
- **Ubicación**: `tots_backend/`
- **Tipo**: API REST
- **Features**: JWT Auth, validaciones, tests, seeders
- [Ver README Backend](tots_backend/README.md)

## 🚀 Quick Start

### Backend

```bash
cd tots_backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan jwt:secret
php artisan serve
```

Servidor en `http://localhost:8000`

### Frontend

```bash
cd tots_front
npm install
npm start
```

Aplicación en `http://localhost:4200`

## 📋 Credenciales de Prueba

```
Email: admin@example.com
Contraseña: password
```

(Usuario admin para ver panel de administración)

## 🏗️ Arquitectura

```
TOTS/
├── tots_backend/          # API REST (Laravel 12)
│   ├── app/
│   │   ├── Http/Controllers/Api/
│   │   │   ├── AuthController.php
│   │   │   ├── SpaceController.php
│   │   │   └── ReservationController.php
│   │   └── Models/
│   │       ├── User.php
│   │       ├── Space.php
│   │       └── Reservation.php
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── tests/
│   └── routes/api.php
│
└── tots_front/            # SPA (Angular 21)
    ├── src/app/
    │   ├── components/
    │   │   ├── home/
    │   │   ├── login/
    │   │   ├── register/
    │   │   ├── spaces/
    │   │   ├── my-reservations/
    │   │   └── reservation-form/
    │   ├── services/
    │   ├── guards/
    │   └── interceptors/
    └── package.json
```

## 🔌 API Endpoints

### Autenticación
- `POST /api/auth/register` - Registro
- `POST /api/auth/login` - Login
- `POST /api/auth/logout` - Logout
- `GET /api/auth/me` - Usuario actual

### Espacios
- `GET /api/spaces` - Listar (filtros: min_capacity, max_capacity, search)
- `GET /api/spaces/{id}` - Detalle
- `POST /api/spaces` - Crear (Admin)
- `PUT /api/spaces/{id}` - Editar (Admin)
- `DELETE /api/spaces/{id}` - Eliminar (Admin)

### Reservas
- `GET /api/reservations` - Mis reservas
- `POST /api/reservations` - Crear
- `PUT /api/reservations/{id}` - Editar
- `DELETE /api/reservations/{id}` - Cancelar
- `GET /api/reservations/available-slots` - Slots libres

## 🗄️ Base de Datos

### Usuarios
- id, name, email, password, is_admin, timestamps

### Espacios
- id, name, description, capacity, location, image_url, hourly_rate, timestamps

### Reservas
- id, space_id, user_id, event_name, start_time, end_time, notes, timestamps

## ✨ Características Principales

### Backend
- ✅ Autenticación JWT
- ✅ CRUD completo para espacios y reservas
- ✅ Validación automática de conflictos horarios
- ✅ Sistema de roles (Admin/Usuario)
- ✅ Tests unitarios e integración
- ✅ Seeders con datos de ejemplo

### Frontend
- ✅ Login/Registro
- ✅ Listado de espacios con filtros
- ✅ Detalles de espacios en modal
- ✅ Crear reservas con validación
- ✅ Gestionar reservas (ver, editar, cancelar)
- ✅ Notificaciones en tiempo real
- ✅ Diseño responsive

## 🛠️ Stack Técnico

### Backend
- Laravel 12
- PHP 8.2+
- MySQL/SQLite
- JWT Auth
- PHPUnit

### Frontend
- Angular 21
- TypeScript 5.9
- Tailwind CSS
- PrimeNG
- RxJS

## 📚 Documentación

Cada carpeta tiene su propio README con instrucciones detalladas:

- [Backend README](tots_backend/README.md)
- [Frontend README](tots_front/README.md)

## 🧪 Testing

### Backend
```bash
cd tots_backend
php artisan test
```

Tests incluyen:
- Autenticación (registro, login)
- CRUD de espacios
- CRUD de reservas
- Validación de conflictos

### Frontend
```bash
cd tots_front
npm test
```

## 🔒 Seguridad

- Autenticación JWT con tokens seguros
- Hash de contraseñas con bcrypt
- Validación en cliente y servidor
- Guards para proteger rutas privadas
- CORS configurado correctamente
- Tokens no se guardan en sesión

## 📱 Responsivo

La aplicación funciona perfectamente en:
- 📱 Móviles (320px+)
- 📱 Tablets (768px+)
- 🖥️ Desktops (1024px+)

## 🎨 Diseño

- Paleta moderna con indigo/blue
- Componentes de PrimeNG
- Tailwind CSS para estilos
- Iconos con PrimeIcons
- Dark mode friendly

## 📝 Lo Más Destacado

### Backend
1. **Validación de conflictos robusta**: Previene cualquier solapamiento de horarios
2. **Tests completos**: Cobertura de casos principales
3. **API RESTful pura**: Endpoints coherentes y bien estructurados
4. **Seeders realistas**: Base de datos pre-poblada

### Frontend
1. **UI moderna y limpia**: Diseño profesional y atractivo
2. **Componentes reutilizables**: Arquitectura escalable
3. **Manejo de errores**: Notificaciones claras
4. **Totalmente responsive**: Perfecto en cualquier pantalla

## 🤝 Contribuciones

Este proyecto fue desarrollado como un desafío técnico para demostrar:
- Dominio de Angular y Laravel
- Implementación correcta de autenticación JWT
- Diseño de API REST
- Testing automático
- Validaciones robustas
- UI/UX moderno

## 📞 Soporte

Para reportar problemas o sugerencias, consulta los archivos README específicos de cada sección.

---

**Desarrollado con ❤️ para reservar espacios de forma fácil y segura.**
