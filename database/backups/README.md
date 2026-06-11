# Respaldos de base de datos (Panda Naicha)

Copias exactas de la base `proyectoprueba` (la que corre en el contenedor Docker `panda_mysql`).
Estos archivos `.sql` son el **respaldo durable**: aunque el contenedor de Docker se borre,
los datos se pueden recuperar desde acá.

## Crear un respaldo nuevo (exportar la base actual a un archivo)

Desde la raíz del proyecto, en PowerShell:

```powershell
cmd /c "docker exec panda_mysql mysqldump -u root -psecret --routines --events proyectoprueba > database\backups\proyectoprueba_AAAA-MM-DD.sql"
```

> Se usa `cmd /c "..."` a propósito: el `>` de PowerShell guardaría el archivo en UTF-16
> y el `.sql` quedaría corrupto. Con `cmd /c` los bytes se guardan tal cual.

## Restaurar un respaldo (volver a cargar los datos en la base)

```powershell
cmd /c "docker exec -i panda_mysql mysql -u root -psecret proyectoprueba < database\backups\proyectoprueba_AAAA-MM-DD.sql"
```

Esto reemplaza el contenido de la base con el del archivo. Útil si la base se borró o se corrompió.

## Datos de conexión (entorno local)

- Contenedor MySQL: `panda_mysql`
- Base: `proyectoprueba`
- Usuario root / password: `root` / `secret`

## Respaldos disponibles

- `proyectoprueba_2026-06-11.sql` — incluye usuarios base (admin/cajero1), productos,
  y el historial de ejemplo del 1 al 16 de junio de 2026 (14 turnos cerrados con ventas,
  movimientos y clasificación del árbol de decisiones).
