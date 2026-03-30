# Pruebas automatizadas en entorno sandbox

## Objetivo

Esta guía explica cómo ejecutar pruebas automatizadas en un entorno aislado (sandbox) usando Docker, sin afectar datos de desarrollo o producción.

## 1. Levantar servicios del sandbox

Desde la raíz del proyecto:

```bash
docker compose up -d --build
```

## 2. Crear base de datos de pruebas aislada

```bash
docker exec -i plataforma-bd mysql -uroot -proot -e "
  CREATE DATABASE IF NOT EXISTS plataforma_test;
  GRANT ALL PRIVILEGES ON plataforma_test.* TO 'admin'@'%';
  FLUSH PRIVILEGES;
  "
```

## 3. Ejecutar pruebas de API en sandbox

```bash
docker exec -it \
  -e APP_ENV=testing \
  -e DB_CONNECTION=mysql \
  -e DB_HOST=db \
  -e DB_PORT=3306 \
  -e DB_DATABASE=plataforma_test \
  -e DB_USERNAME=admin \
  -e DB_PASSWORD=admin \
  laravel-app php artisan test --filter=Api
```

## 4. Ejecutar una suite específica

```bash
docker exec -it \
  -e APP_ENV=testing \
  -e DB_CONNECTION=mysql \
  -e DB_HOST=db \
  -e DB_PORT=3306 \
  -e DB_DATABASE=plataforma_test \
  -e DB_USERNAME=admin \
  -e DB_PASSWORD=admin \
  laravel-app php artisan test --filter=ApiOrdersTest
```

## 5. Limpieza y reinicio del sandbox

```bash
docker compose down
```

Reinicio limpio borrando volúmenes:

```bash
docker compose down -v
docker compose up -d --build
```
