# Instalación con Docker

Esta guía levanta el proyecto con `Docker Compose`, configura variables de entorno y muestra comandos de uso diario con `docker exec -it`.

## 1. Requisitos

- Docker
- Docker Compose plugin (`docker compose`)

Verificar:

```bash
docker --version
docker compose version
```

## 2. Configurar variables de entorno

Crear el archivo `.env` desde el ejemplo:

```bash
cp .env.example .env
```

Variables clave (alineadas con `docker-compose.yml`):

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=plataforma_db
DB_USERNAME=admin
DB_PASSWORD=admin
```

## 3. Levantar contenedores

Desde la raíz del proyecto:

```bash
docker compose up -d --build
```

Servicios:

- `app` (`laravel-app`) en `http://localhost:8000`
- `db` (`plataforma-bd`) en `localhost:3307` (host) -> `3306` (contenedor)
- `node` (`laravel-node`) en `http://localhost:5173`

## 4. Inicializar Laravel dentro del contenedor

Instalar dependencias PHP:

```bash
docker exec -it laravel-app composer install
```

Generar clave de aplicación:

```bash
docker exec -it laravel-app php artisan key:generate
```

Ejecutar migraciones:

```bash
docker exec -it laravel-app php artisan migrate
```

Opcional: migrar con seeders:

```bash
docker exec -it laravel-app php artisan migrate --seed
```

## 5. Detener y reiniciar

Detener servicios:

```bash
docker compose down
```

Detener y borrar volúmenes (reinicio limpio de base de datos):

```bash
docker compose down -v
```

Volver a levantar:

```bash
docker compose up -d --build
```
