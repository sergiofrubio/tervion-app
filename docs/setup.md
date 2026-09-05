# Guía de Inicialización del Entorno de Desarrollo (Local Setup)

Este documento detalla los pasos y especificaciones técnicas necesarias para levantar el entorno de desarrollo local de **Tervion ERP** de forma totalmente aislada utilizando contenedores Docker.

---

## 🛠️ Requisitos Previos

Para levantar el entorno completo, tu máquina host debe disponer de:
- **Docker Desktop** (versión 20.10+ recomendada)
- **Docker Compose V2** (integrado de forma nativa en las versiones modernas de Docker)

> [!TIP]
> No es estrictamente necesario tener PHP ni MySQL instalados a nivel de sistema operativo local en la máquina host, ya que el contenedor `php` (PHP-FPM) gestiona el runtime de ejecución de PHP con todas las extensiones necesarias, `caddy` actúa como servidor web/SSL y `db` provee MySQL/MariaDB.

---

## 🚀 Levantamiento Rápido (Quickstart)

Sigue estos 3 pasos para poner el sistema en funcionamiento:

1. **Clonar e Ingresar al Proyecto:**
   ```bash
   git clone https://github.com/sergiofrubio/tervion-app.git
   cd tervion-app
   ```

2. **Levantar los Contenedores:**
   ```bash
   docker compose up -d --build
   ```

3. **Acceder a los Servicios:**
   El entorno cuenta con `compose.override.yml` habilitando la configuración local.
   
   - **Aplicación Web:** [http://localhost](http://localhost)
   - **PHPMyAdmin (Gestión de BD):** [http://localhost:8080](http://localhost:8080)
   - **Mailpit (Intercepción de Correos):** [http://localhost:8025](http://localhost:8025)

---

## 📦 Arquitectura de Contenedores (Docker Services)

El entorno local consta de los siguientes contenedores definidos en `compose.yml` y `compose.override.yml`:

| Contenedor | Imagen / Target | Puerto Host | Propósito |
| :--- | :--- | :--- | :--- |
| **caddy** | `caddy:2-alpine` | `80:80`, `443:443` | Servidor Web Caddy con SSL automático y entrega de estáticos. |
| **php** | `Dockerfile` (target: `development`) | Interno `9000` | Intérprete PHP-FPM + extensiones + Xdebug. |
| **db** | `mariadb:10.11` | Interno `3306` | Servidor de base de datos MariaDB. |
| **phpmyadmin** | `phpmyadmin/phpmyadmin` | `8080:80` | Interfaz gráfica web para administración de base de datos. |
| **mailpit** | `axllent/mailpit` | `8025` (Web UI), `1025` (SMTP) | Servidor SMTP simulado para atrapar correos salientes y debuggear notificaciones sin enviar emails reales. |

---

## 🔑 Credenciales por Defecto

### 1. Aplicación Web (Acceso Inicial)
El sistema carga fixtures por defecto al iniciar la base de datos:
- **Usuario Administrador:** `admin@example.com`
- **Contraseña:** `12345678`

### 2. Base de Datos (MySQL / MariaDB)
- **Host:** `db` (o `127.0.0.1` si conectas desde el host)
- **Puerto:** `3306`
- **Base de datos:** `app`
- **Usuario root:** `root`
- **Contraseña:** `root`

### 3. Servicios Auxiliares
- **Mailpit Web UI:** [http://localhost:8025](http://localhost:8025)
- **phpMyAdmin Web UI:** [http://localhost:8080](http://localhost:8080)

---

## 🔄 Inicialización Automática de la Base de Datos

El contenedor de base de datos (`db`) monta un volumen con el esquema SQL del proyecto:
- **Archivo origen:** `data/tervion.sql`
- **Montaje en contenedor:** `/docker-entrypoint-initdb.d/init.sql`

> [!IMPORTANT]
> El script SQL se ejecuta **únicamente la primera vez** que el volumen de la base de datos (`db_data`) es creado. Si realizas cambios en el esquema y deseas reiniciar de cero:
> ```bash
> docker compose down -v
> docker compose up -d --build
> ```

---

## 🛠️ Comandos Frecuentes

```bash
# Ver estado de los contenedores
docker compose ps

# Ver logs en tiempo real
docker compose logs -f

# Ver logs únicamente de PHP
docker compose logs -f php

# Ver logs únicamente de Caddy
docker compose logs -f caddy

# Acceder al contenedor de PHP mediante shell interactivo
docker compose exec php bash

# Detener los contenedores sin borrar volúmenes
docker compose down

# Detener y borrar volúmenes (reinicia la BD desde cero)
docker compose down -v
```

---

## ⚠️ Solución de Problemas Comunes

### 1. El puerto 80 o 443 ya está en uso en el host
Si tienes un servicio local (como IIS o Skype) ocupando los puertos:
1. Abre `compose.yml`.
2. Mapea el puerto a uno libre en el servicio `caddy` (ej. `"8081:80"`).
3. Reinicia los contenedores (`docker compose up -d`).

### Error al conectar con la base de datos
Si la aplicación web muestra errores de conexión PDO, comprueba que el contenedor `db` esté saludable:
```bash
docker compose ps
docker compose logs db
```
