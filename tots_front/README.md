# Frontend - TOTS

SPA con Angular 17 para el sistema de reservas.

## Qué hace

- Los usuarios pueden ver espacios, reservarlos, gestionar sus reservas. 
- Los admins además pueden administrar los espacios.

El calendario está disponible en el modal |Ver detalles| -> |Disponibilidad| en la url `/spaces`

## Stack

- Angular 17 (standalone components, sin modules)
- TypeScript 5.4
- PrimeNG 17 para componentes UI (Calendar, Dialog, Table, etc.)
- Tailwind CSS para estilos
- RxJS para manejo de estado y HTTP
- Vite para build

## Instalación

```bash
cd tots_front

# Instalar dependencias
npm install --legacy-peer-deps

# Levantar el servidor
npm start
```

La app estará en `http://localhost:4200`

**Nota:** El `--legacy-peer-deps` es necesario por algunas dependencias de PrimeNG. Es normal.

## Estructura

```
src/app/
├── components/
│   ├── home/                          # Landing page
│   ├── navbar/                        # Barra de navegación
│   ├── login/                         # Login
│   ├── register/                      # Registro
│   ├── spaces/                        # Listado de espacios
│   ├── space-availability-calendar/   # El calendario (lo más interesante)
│   ├── reservation-form/              # Formulario de reserva
│   ├── my-reservations/               # Mis reservas
│   └── admin-spaces/                  # Panel admin (CRUD espacios)
│
├── services/
│   ├── auth.service.ts                # Login, registro, JWT
│   ├── space.service.ts               # CRUD espacios
│   ├── reservation.service.ts         # CRUD reservas
│   └── notification.service.ts        # Toasts
│
├── guards/
│   ├── auth.guard.ts                  # Proteger rutas (requiere login)
│   └── admin.guard.ts                 # Solo admins
│
├── interceptors/
│   └── jwt.interceptor.ts             # Agregar token a requests
│
├── app.routes.ts                      # Definición de rutas
└── app.config.ts                      # Configuración de la app
```

## Rutas

| Ruta | Componente | Público | Auth | Admin |
|------|-----------|---------|------|-------|
| `/` | Home | Sí | - | - |
| `/login` | Login | Sí | - | - |
| `/register` | Register | Sí | - | - |
| `/spaces` | Spaces | - | Sí | - |
| `/my-reservations` | MyReservations | - | Sí | - |
| `/reservations/new` | ReservationForm | - | Sí | - |
| `/admin` | AdminSpaces | - | Sí | Sí |

Las rutas con "Auth" requieren estar logueado. Las de "Admin" requiere que el usuario en linea sea Admin.

## Componentes principales

### Spaces

Listado de espacios con filtros. Muestra cards con info básica de cada espacio. Al hacer click se abre un modal con dos pestañas:
- **Detalles**: Info completa del espacio
- **Disponibilidad**: El calendario

Los filtros incluyen:
- Búsqueda por nombre
- Tipo (sala, auditorio, conferencia, taller)
- Capacidad (min-max)
- Fecha de disponibilidad

### Space Availability Calendar

Muestra un calendario con selector de fecha y un grid de horas disponibles.

**Cómo funciona:**
1. Seleccionas una fecha
2. Se hace un request a `/api/spaces/{id}/available-slots?date=YYYY-MM-DD`
3. El backend devuelve qué horas están libres
4. Se muestran en un grid: verde = disponible, gris = ocupado
5. Si haces click en una hora disponible, emite un evento con la fecha/hora
6. El componente padre recibe el evento y abre el formulario de reserva pre-llenado

## Notas finales

El frontend está completo y funcional, se añade la función de Calendario. El diseño es estilo dark y responsive. La autenticación funciona correctamente con JWT.

El código está limpio. Los componentes son standalone (Angular 17 style). Los services encapsulan la lógica de API. Los guards protegen las rutas.

---
