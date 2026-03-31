## Opción 1: Instalación con Docker (Linux recomendado)

Esta opción levanta todo el entorno (backend, base de datos, etc.) usando **Docker Compose**, lo que garantiza consistencia entre entornos.

### 1. Requisitos

- Docker
- Docker Compose Plugin (`docker compose`)

Verifica la instalación:

```bash
docker --version
docker compose version
```

---

### 2. Configuración de variables de entorno

Copia el archivo de entorno base:

```bash
cp .env.example .env
```

---

### 3. Levantar el entorno

Construye y levanta los contenedores:

```bash
docker compose up --build
```

Este comando:

* Construye las imágenes necesarias
* Levanta los servicios definidos (app, base de datos, etc.)
* Ejecuta el proyecto en un entorno aislado

---

## Opción 2: Instalación manual (Windows recomendado)

Esta opción ejecuta el proyecto directamente en tu máquina sin contenedores.

---

### 1. Configurar variables de entorno

```bash
cp .env.example .env
```

---

### 2. Instalar dependencias

```bash
composer install
npm install
```

---

### 3. Generar clave de aplicación

```bash
php artisan key:generate
```

---

### 4. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```
