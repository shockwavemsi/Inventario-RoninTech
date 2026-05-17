# 📦 Inventario RoninTech

Aplicación web de gestión de inventario desarrollada con **Laravel**, diseñada para administrar productos, stock, proveedores y movimientos de almacén.  
El proyecto está completamente dockerizado para facilitar su instalación, ejecución y despliegue en cualquier entorno.

---

## 🚀 Tecnologías utilizadas

- ⚙️ **Laravel 10**
- 🐘 **PHP 8.2**
- 🗄️ **MySQL 5.7**
- 🌐 **Nginx 1.17**
- 🐳 **Docker**
- 🐳 **Docker Compose**

---

## 📋 Requisitos previos

Antes de comenzar, asegúrate de tener instalado:

- Docker
- Docker Compose
- Git (opcional)

---

## ⚙️ Instalación del proyecto

### 1. Clonar el repositorio

```bash
git clone https://github.com/shockwavemsi/Inventario-RoninTech.git
cd Inventario-RoninTech
```

### 2. Configurar las variables de entorno

```bash
cp .env.example .env
```

> ⚠️ Asegúrate de que las variables de base de datos en `.env` coincidan con las definidas en `docker-compose.yml`.

---

### 3. Levantar los contenedores Docker

```bash
docker compose up -d
```

---

### 4. Ejecutar migraciones y seeders

```bash
docker exec -it laravel-app php artisan migrate --seed
```

---

## ▶️ Ejecución de la aplicación

### Iniciar la aplicación

```bash
docker compose up -d
```

La aplicación estará disponible en:

```text
http://localhost:8000
```

---

### Detener la aplicación

```bash
docker compose down
```

---

### Ver logs de los contenedores

```bash
docker compose logs -f
```

---

## ☁️ Despliegue en producción

El proyecto está preparado para ejecutarse localmente mediante Docker, pero también puede desplegarse en servicios cloud o VPS compatibles con contenedores, como:

- Railway
- AWS ECS
- DigitalOcean
- VPS con Docker instalado

Solo será necesario ajustar:

- Variables de entorno
- Puertos expuestos
- Configuración de dominio y SSL

---

## 📁 Estructura del proyecto

```text
Inventario-RoninTech/
├── app/                    # Código fuente principal de Laravel
├── bootstrap/              # Archivos de arranque del framework
├── config/                 # Configuraciones de Laravel
├── database/               # Migraciones, seeders y factories
├── docker-compose/         # Configuración adicional de Docker
│   ├── mysql/              # Scripts de inicialización de MySQL
│   └── nginx/              # Configuración de Nginx
├── public/                 # Punto de entrada público (index.php)
├── resources/              # Vistas, assets y traducciones
├── routes/                 # Definición de rutas
├── storage/                # Logs, caché y archivos subidos
├── tests/                  # Pruebas unitarias y funcionales
├── .env.example            # Ejemplo de variables de entorno
├── docker-compose.yml      # Orquestación de contenedores
├── Dockerfile              # Construcción de la imagen Docker
└── README.md               # Documentación del proyecto
```

---

## Autor

Proyecto desarrollado por **RoninTech**.