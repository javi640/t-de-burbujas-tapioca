# CLAUDE.md

Contexto para Claude Code (claude.ai/code) cuando trabaja en este repositorio.

## Qué es este proyecto

**Panda Naicha** — sistema de gestión integral para tienda de bubble tea en La Paz, Bolivia. App Laravel 12 standalone. Dos roles: **Admin** y **Cajero**. El núcleo académico es un **árbol de decisiones multivariable** que clasifica inconsistencias de caja al cierre de turno.

> ⚠️ El cajero **NO abre su propio turno** — esa decisión de diseño es importante. El admin abre los turnos para los cajeros: formulario en `admin.shifts.open` (GET) y creación en `admin.shifts.store` (POST → `AdminShiftController::openForCajero()`). Cuando un cajero hace login sin turno asignado, ve la pantalla de espera (`cajero.shift.waiting`).

## Stack (verificado en el repo)

- **Backend**: Laravel 12, PHP 8.2+
- **Base de datos**: MySQL 8.0 — schema `proyectoprueba`
- **Frontend**: Blade + **CSS custom inline en el layout** (tema claro, ~660 líneas en `layouts/app.blade.php`). Carga **Bootstrap 5 + Bootstrap Icons por CDN** en el `<head>` del layout (agregado recientemente; el grueso de la UI sigue usando el CSS custom inline). **Tailwind está instalado en `package.json` pero NO se usa** (no se compila). Tampoco usa `@vite()`.
- **Fuentes**: Google Fonts — **DM Sans** (texto) y **DM Mono** (montos y números)
- **Iconos**: mezcla — **SVG inline** (nav del sidebar, iconos de auditoría), caracteres unicode (✓, ⚠, ✕ en el `DecisionTreeService` y botones), y **Bootstrap Icons** disponible vía CDN.
- **Infra**: Docker Compose con 3 servicios — `panda_app` (PHP-FPM), `panda_nginx` (puerto **8080**), `panda_mysql` (8.0, puerto local **3307**)
- **Timezone**: `America/La_Paz` (configurado en `config/app.php` y `docker-compose.yml`)
- **Testing**: Pest 3 + pest-plugin-laravel (configurado, sin tests escritos todavía)
- **Code style**: Laravel Pint 1.24
- **Dev tools**: Laravel Boost 2.0, Pail, Sail, Collision, Faker

## Comandos

```bash
# ── Docker (uso normal) ──────────────────────────────────────────
docker compose up --build           # primera vez (2-3 min)
docker compose up -d                # uso normal
docker compose down                 # parar
docker compose down -v              # parar y BORRAR la BD (cuidado)
docker compose logs -f              # logs en vivo

# ── Entrar al contenedor o correr Artisan desde fuera ───────────
docker exec -it panda_app bash
docker exec panda_app php artisan migrate
docker exec panda_app php artisan migrate:fresh --seed
docker exec panda_app php artisan tinker
docker exec panda_app php artisan config:clear

# ── Tests con Pest ────────────────────────────────────────────────
docker exec panda_app php artisan test
docker exec panda_app php artisan test --filter=DecisionTreeTest
docker exec panda_app ./vendor/bin/pest

# ── Pint (antes de finalizar cambios) ────────────────────────────
docker exec panda_app ./vendor/bin/pint --dirty
```

App en `http://localhost:8080`. MySQL local en `127.0.0.1:3307` (no 3306, para evitar choques con MySQL del host).

**Credenciales seed (solo local)**:
- Admin: `admin / admin123`
- Cajero: `cajero1 / cajero123`

> Login es por `username`, no por email. El reset de contraseña sí usa email, pero solo acepta dominios `@gmail.com` (regex hardcoded en `AuthController::sendResetLink`).

## Arquitectura

### Flujo de negocio principal

```
Admin abre turno a cajero  →  Cajero inicia sesión (PUNTUAL/TARDANZA)
                              →  Registra ventas (CASH/QR) + movimientos de caja
                              →  Cierra turno declarando efectivo
                              →  DecisionTreeService clasifica diferencia
                              →  Admin revisa reportes y dashboard
```

### Componente crítico: DecisionTreeService (aporte académico)

`app/Services/DecisionTreeService.php` — clasifica automáticamente las inconsistencias de caja al cierre de turno usando 5 variables.

**Punto de entrada único**: `evaluate(float $expectedCash, float $reportedCash, Shift $shift): array`.

**Constantes** (NO hardcodear valores en la lógica, modificar acá):
```php
const THRESHOLD_OK           = 0;       // |diff| = 0 → cuadrado
const THRESHOLD_LEVE         = 20.00;   // |diff| ≤ Bs. 20 → leve
const REINCIDENCE_WINDOW     = 3;       // últimos 3 turnos cerrados
const REINCIDENCE_MIN        = 2;       // ≥2 con inconsistencia → reincidente
const QR_THRESHOLD_PERCENT   = 30.0;    // ≥30% QR → faltante "explicable"
```

**5 variables del árbol** (leídas de BD al cerrar):
- **V1** — Diferencia de arqueo (`reported_cash - expected_cash`)
- **V2** — Reincidencia del cajero (últimos 3 turnos cerrados)
- **V3** — Faltante vs sobrante
- **V4** — Proporción de ventas QR del turno
- **V5** — Existencia de egresos registrados

**Salidas**: `SIN_INCONSISTENCIA` | `INCONSISTENCIA_LEVE` | `INCONSISTENCIA_CRITICA` (persistido en `shifts.inconsistency_class`).

**Importante**: cualquier cambio debe mantener en el array de respuesta:
- `decision_path` (recorrido nodo a nodo, traza de auditoría para defensa académica)
- `thresholds` (transparencia del modelo)

### Modelos clave (`app/Models/`)

| Modelo | Notas |
|---|---|
| `User` | Login por `username`. Tiene `branch_id`, `role_id`, `is_active`. Métodos: `isAdmin()`, `isCajero()`, `openShift()`, `hasOpenShift()`. |
| `Role` | Solo dos: `admin` y `cajero`. Existen constantes `Role::ADMIN`/`Role::CAJERO` pero **no se usan**: el código compara con los strings `'admin'`/`'cajero'` (`User::isAdmin()`, `RoleMiddleware`). |
| `Branch` | Sucursal. Por ahora una sola. |
| `Shift` | Entidad central. Status `OPEN`/`CLOSED`. Métodos críticos: `expectedCash()`, `totalQr()`, `isLate()`, `registerCajeroLogin()`. |
| `ShiftStock` | Stock por turno por producto. Unique `[shift_id, product_id]`. Métodos `remainingQuantity()`, `hasStock(qty)`. |
| `Product` | `name`, `price`, `is_active`. Scope `active()`. |
| `Sale` + `SaleDetail` | `payment_method` ∈ `{CASH, QR}`. Status `{COMPLETED, VOIDED}`. Anulable con `voided_by` + `void_reason`. |
| `CashMovement` | `INCOME` / `EXPENSE` durante el turno. |
| `AuditLog` | Acciones críticas con `old_values`/`new_values` (JSON) + `ip_address`. |

### Controladores

- **Cajero**: `ShiftController`, `SaleController`, `CashMovementController`
- **Admin**: `AdminDashboardController`, `AdminShiftController`, `AdminSaleController`, `AdminReportController`, `AdminAuditController`, `UserController`
- **Auth**: `AuthController`
- **Público**: `Public\HomeController` (landing en `/`)

Todos heredan de `Controller` abstracto que provee `authUser(): \App\Models\User`. **Preferir `$this->authUser()` sobre `Auth::user()`** en controllers. Deviación actual: `SaleController` y `CashMovementController` usan `Auth::user()`/`Auth::id()` directo — al tocar esos archivos, migrar a `$this->authUser()`.

### Middleware (`app/Http/Middleware/`)

- `role` — `RoleMiddleware`. Uso: `'role:admin'` o `'role:cajero'`. También valida `is_active` y desloguea cuentas inactivas.
- `prevent-cache` — `PreventCache`. Headers no-store para rutas autenticadas (importante en terminales compartidas).

Registrados en `bootstrap/app.php` (estructura Laravel 12).

### Rutas (`routes/web.php`)

Sin `routes/api.php`. Grupos:
- **público** → `/` (landing, name `home` → `Public\HomeController`)
- `guest` → `/login`, `/forgot-password`, `/reset-password/{token}`
- `auth + role:cajero + prevent-cache` → `/cajero/*` (waiting, current, close, summary, sales, movements)
- `auth + role:admin + prevent-cache` → `/admin/*` (dashboard, users, shifts, sales, reports, audit)

### Vistas (`resources/views/`)

- `layouts/app.blade.php` — layout único maestro (CSS inline, sidebar + topbar, tema claro)
- `public/home.blade.php` — landing pública (sin auth)
- `auth/` — login, forgot, reset
- `admin/{users,shifts,sales,reports,audit}/`
- `cajero/{shift,sales}/` — nota: `cajero/shift/open.blade.php` es **legacy/huérfana** (el cajero ya no abre turnos; sin ruta)

## Reglas del proyecto

### Transacciones obligatorias

Estas operaciones deben ejecutarse dentro de `DB::transaction()`:
- **Apertura de turno** (crea `Shift` + N `ShiftStock`)
- **Registro de venta** (valida stock con `lockForUpdate()` + crea `Sale` + N `SaleDetail` + actualiza `ShiftStock`)
- **Anulación de venta** (revierte stock + marca `VOIDED`)
- **Cierre de turno** (invoca `DecisionTreeService` + guarda diferencia + crea AuditLog)

### Lock pesimista en stock

`SaleController::store()` usa `lockForUpdate()` en `ShiftStock` para evitar race conditions cuando dos pedidos compiten por el mismo producto. **Mantener este lock** al modificar lógica de ventas.

### Auditoría

Toda acción sensible debe registrar un `AuditLog`. Acciones que realmente se escriben (7): `login`, `logout`, `cajero_login_registered`, `open_shift_for_cajero`, `close_shift`, `void_sale`, `cash_movement`. (`AdminAuditController` además tiene una etiqueta `open_shift` legacy que ya **no se genera**.) **No inventes nuevas** — reutiliza estas o consulta antes.

### Caché del dashboard

`AdminDashboardController` usa caché agresivo (TTL 30s) con 3 keys: `dashboard.active_shifts`, `dashboard.period.hoy.{from}.{to}`, `dashboard.period.semana.{from}.{to}`. `ShiftController::close()` ya invalida estas keys con `Cache::forget()`.

**Toda operación nueva que afecte KPIs del dashboard debe invalidar caché correspondiente** tras commitear la transacción, o el admin verá datos viejos por 30s.

### Validación

Preferir Form Requests existentes (`app/Http/Requests/`), una por endpoint que valida. Deviaciones actuales que validan **inline**: `AdminShiftController::openForCajero()`, `SaleController::void()` y `AuthController::resetPassword()`/`sendResetLink()`. Nota: `OpenShiftRequest` existe pero está **huérfano** (no lo usa `openForCajero`). Al tocar esos endpoints, considerar mover la validación a un Form Request.

### Money / Decimales

Bolivianos (Bs). Cast `decimal:2` en modelos. Columnas `decimal(10, 2)` en migraciones. **Nunca uses `float` para comparar diferencias de caja** — usa el `DecisionTreeService` y sus umbrales.

### Convenciones Eloquent

- Usar `Model::query()`. Evitar `DB::` raw salvo agregaciones complejas (ej. `SUM(CASE WHEN payment_method='QR' THEN ...)`)
- **Eager loading obligatorio**: los controladores admin ya usan `with()` para evitar N+1 — mantener el patrón
- Scopes existentes: `Sale::completed()`, `Product::active()`, `Shift::open()`, `Shift::closed()`

### Migraciones y BD

- **Enums en MySQL** como columnas `enum()` para valores cerrados (`shifts.status`, `sales.status`, `sales.payment_method`, `cash_movements.movement_type`). Mantener el patrón.
- **Valores de enum siempre en MAYÚSCULAS**: `OPEN`, `CLOSED`, `COMPLETED`, `VOIDED`, `CASH`, `QR`, `INCOME`, `EXPENSE`, `PENDIENTE`, `PUNTUAL`, `TARDANZA`.
- **Inconsistency class con underscore**: `SIN_INCONSISTENCIA`, `INCONSISTENCIA_LEVE`, `INCONSISTENCIA_CRITICA`.
- Índice compuesto crítico: `shifts(user_id, status)` se consulta en cada login de cajero — no removerlo.
- Al modificar una columna existente, incluir TODOS los atributos previos o se pierden.

### Casts en modelos

El código existente usa propiedad `$casts` (no método `casts()`). Mantener consistencia para no mezclar estilos.

### Idioma

- **Código en inglés** (`expectedCash`, `isReincident`, `qrExplainsDifference`)
- **Vistas y mensajes en español**

## Reglas de dominio específicas

- **Un cajero no puede tener dos turnos abiertos al mismo tiempo**. `User::openShift()` es HasOne con filtro de status `OPEN`.
- **El cajero NO abre turnos** — apertura es del admin vía `AdminShiftController::openForCajero()`. Habilitar al cajero a auto-abrir es cambio de diseño que requiere discusión.
- **No se puede registrar ventas ni movimientos sin un turno abierto del usuario**. Los controllers del cajero hacen `$user->openShift()->firstOrFail()` y revientan con 404 si no hay.
- **Ventas no se modifican, se anulan**. Cambia `status` a `VOIDED` con `voided_by` + `void_reason`. Solo el admin puede anular (`POST /admin/venta/{sale}/anular`).
- **Asistencia del cajero**: `Shift::registerCajeroLogin()` compara `cajero_login_time` con `scheduled_start + tolerance_minutes`. Resultado en `attendance_status` ∈ `{PENDIENTE, PUNTUAL, TARDANZA}` (PENDIENTE se asigna al abrir el turno, antes del primer login del cajero).

## Trampas conocidas

- **Login por username, reset por email**: las credenciales son `username + password` (cajeros bolivianos no necesariamente tienen email). El reset sí requiere email, restringido a `@gmail.com` por regex en `AuthController::sendResetLink`.
- **MySQL puerto 3307**: para evitar choques con MySQL del host (Laragon, XAMPP, etc.). Conexiones desde fuera de Docker usan `127.0.0.1:3307`.
- **Toppings vs Bebidas**: no hay columna que los distinga. La separación se hace por keywords en el nombre del producto (`boba`, `jelly`, `pearls`, `nata`, `crumbs`, `pudding`) en `SaleController::create()`. Si crece la lista de productos, considerar agregar columna `category` a `products`.
- **`prevent-cache` middleware**: aplicado en TODAS las rutas autenticadas + logout. No removerlo aunque parezca redundante — protege en terminales compartidas.
- **CSS inline en el layout** (~660 líneas): cualquier cambio visual grande probablemente toque ese archivo. Si crece más, considerar mover a asset CSS dedicado — pero hacerlo de forma consistente, no a medias.
- **Tailwind instalado pero sin usar**: si decidís activarlo, hacerlo en todo el proyecto a la vez. Mezclar Tailwind con CSS custom inline será un desastre.
- **Bootstrap 5 cargado por CDN pero UI casi toda custom**: el layout incluye Bootstrap 5 + Bootstrap Icons, pero el grueso de la UI usa el CSS inline. Ojo con colisiones de nombres de clase (`.btn`, `.badge` existen en ambos). La paginación de las listas (Ventas/Turnos/Auditoría) se arma a mano con estilos inline del tema, **no** con `->links()`.

## Testing

- **Estado actual**: Pest 3 configurado, sin tests propios (solo los `ExampleTest.php` placeholder en `tests/Feature` y `tests/Unit`). `Pest.php` tiene `RefreshDatabase` comentado.
- **Al empezar a escribir tests**:
  - Crear con `php artisan make:test --pest NombreTest` (feature) o `--unit --pest`
  - Activar `RefreshDatabase` en `Pest.php` descomentando la línea correspondiente
  - **Prioridad #1: `DecisionTreeService`** — un test por cada rama del árbol (7 ramas mínimo: sin diferencia, leve sin reincidencia, leve con reincidencia, faltante con QR ≥30%, faltante con QR <30%, sobrante con egresos, sobrante sin egresos)
- Ejecutar: `./vendor/bin/pest --filter="nombre"`

## Estructura Laravel 12 (recordatorios)

- Middleware se registra en `bootstrap/app.php` (no en `app/Http/Kernel.php` — ya no existe)
- Service providers en `bootstrap/providers.php`
- Comandos en `app/Console/Commands/` se auto-registran

## Antes de cambios grandes

- **Cambios al árbol de decisiones** (umbrales, lógica, variables): es el aporte académico — discutir con el equipo. Cualquier cambio debe ir acompañado de tests que cubran las ramas afectadas.
- **Cambios al esquema de `shifts` o `users`**: impactan toda la app y reportes históricos.
- **Migrar CSS inline a Vite/asset**: cambio grande, hacerlo completo o no hacerlo.
- **Activar Tailwind**: ver "Trampas conocidas" — todo o nada.

<laravel-boost-guidelines>
# Laravel Boost

Este proyecto tiene Laravel Boost 2.0 instalado (MCP server). Úsalo activamente cuando sea posible:

- **`search-docs`** — Documentación versión-específica de Laravel 12 y paquetes instalados. Úsalo ANTES de adivinar APIs. Pasa queries simples y múltiples: `['eloquent transaction', 'database transaction']`. No incluyas nombres de paquetes en las queries — ya se filtran automáticamente.
- **`tinker`** — Ejecutar PHP para debug o consultas Eloquent.
- **`database-query`** — Lectura directa de BD (solo SELECT).
- **`database-schema`** — Esquema actualizado de tablas.
- **`list-artisan-commands`** — Verificar parámetros antes de invocar Artisan.
- **`list-routes`** — Mapa actual de rutas registradas.
- **`read-log-entries`** / **`last-error`** — Logs recientes para diagnóstico.
- **`get-absolute-url`** — URLs correctas con scheme/puerto del proyecto (`http://localhost:8080`).

## Convenciones PHP

- Siempre llaves `{}` en estructuras de control, incluso para una línea
- Constructor property promotion: `public function __construct(public Service $s) {}`
- Tipado explícito en parámetros y return types: `function foo(int $id): bool`
- Preferir PHPDoc sobre comentarios inline. Sin comentarios salvo lógica excepcionalmente compleja

## Convenciones Laravel

- Usar `php artisan make:*` para crear archivos (con `--no-interaction`)
- Form Requests para validación, nunca inline en controladores
- `route('nombre')` en lugar de URLs hardcodeadas
- `config('app.name')` en código, nunca `env()` fuera de `config/`
- Factories + seeders al crear nuevos modelos

## Pint

Antes de finalizar cambios:
```bash
./vendor/bin/pint --dirty
```

No hay `pint.json` custom — usa la configuración por defecto de Laravel.
</laravel-boost-guidelines>

## Herramientas opcionales recomendadas

- **Laravel Debugbar** (`barryvdh/laravel-debugbar`): debug en desarrollo, muestra queries, tiempos, memoria. Útil para detectar N+1 en dashboards. Solo `require-dev`.
- **Telescope** (`laravel/telescope`): dashboard de debug más completo. Útil si crece el volumen de auditoría.
- Activar Pint en pre-commit hook si trabajan varias personas en el repo.