# Frontend - Aplicación de Reserva de Espacios

Una SPA (Single Page Application) construida con **Angular 21** para reservar espacios para eventos.

## Características

- ✅ Autenticación JWT
- ✅ Sistema de registración e inicio de sesión
- ✅ Listado de espacios con filtros
- ✅ Creación y gestión de reservas
- ✅ Vista detallada de espacios
- ✅ UI moderna con PrimeNG y Tailwind CSS
- ✅ Notificaciones en tiempo real (toasts)
- ✅ Responsive design

## Stack Técnico

- Angular 21
- TypeScript
- RxJS
- PrimeNG (componentes UI)
- Tailwind CSS
- Vite

## Instalación

```bash
cd tots_front
npm install
npm start
```

Disponible en `http://localhost:4200`

## Estructura del Proyecto

```
src/app/
├── components/
│   ├── home/              # Página de inicio
│   ├── login/             # Login
│   ├── register/          # Registro
│   ├── navbar/            # Navegación
│   ├── spaces/            # Listado espacios
│   ├── reservation-form/  # Crear reserva
│   └── my-reservations/   # Gestionar reservas
├── services/              # APIs y lógica
├── guards/                # Protección rutas
└── interceptors/          # JWT token
```

## Páginas Principales

- **`/`** - Inicio
- **`/login`** - Iniciar sesión
- **`/register`** - Crear cuenta
- **`/spaces`** - Listar espacios (privado)
- **`/my-reservations`** - Mis reservas (privado)
- **`/reservations/new`** - Nueva reserva (privado)

## Características Principales

1. **Autenticación JWT**: Token seguro almacenado en localStorage
2. **Filtros de espacios**: Por capacidad y búsqueda de texto
3. **Gestión de reservas**: Crear, editar y cancelar
4. **Notificaciones**: Toasts de éxito, error e información
5. **Responsive**: Móvil, tablet y desktop
6. **Guards automáticos**: Redirección a login si no está autenticado

## Componentes Destacados

- **Card**: Tarjetas de espacios y información
- **Table**: Tabla de reservas con paginación
- **Dialog**: Modales para detalles y edición
- **Calendar**: Selector de fecha y hora
- **Toast**: Notificaciones automáticas
- **Button**: Botones con iconos

## Servicios

- **AuthService**: Registro, login, token, usuario actual
- **SpaceService**: CRUD de espacios con filtros
- **ReservationService**: CRUD de reservas
- **NotificationService**: Notificaciones (toasts)

## Interceptor JWT

Automáticamente añade el token a todas las requests:
```
Authorization: Bearer <token>
```

## Validaciones

- ✓ Email válido
- ✓ Contraseñas coinciden
- ✓ Horarios disponibles
- ✓ Campos requeridos
- ✓ Formatos de fecha/hora

## Build

```bash
npm run build
```

Salida en `dist/`

## Desarrollo

```bash
npm start
```

Servidor en puerto 4200 con hot reload.

## Lo Mejor del Frontend

1. **UI moderna**: Diseño limpio con Tailwind + PrimeNG
2. **Sin dependencias CSS**: Solo Tailwind y PrimeNG
3. **Componentes standalone**: Arquitectura moderna de Angular
4. **Responsive**: Perfectamente adaptable a cualquier pantalla
5. **Mensajes claros**: Notificaciones descriptivas para el usuario
6. **Accesible**: Buenas prácticas de a11y

## Requisitos

- Node.js 20+
- npm 10+
- Backend en `http://localhost:8000`

## Notas Importantes

- Tokens JWT expiran en 24 horas
- Solo se ven tus propias reservas
- Edita solo el nombre del evento y notas
- Horarios se validan automáticamente
