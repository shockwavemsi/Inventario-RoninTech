FROM php:8.4-fpm

# 1. Instalamos dependencias del sistema
# Se añade libpq-dev para que funcione PostgreSQL (la DB gratis de Render)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip

# 2. Limpieza de caché de paquetes
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Instalamos extensiones de PHP
# Se añade pdo_pgsql para la base de datos de Render
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo_mysql pdo_pgsql mbstring exif pcntl bcmath zip

# 4. Traemos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Directorio de trabajo
WORKDIR /var/www

# 6. Copiamos los archivos del proyecto
COPY . .

# 7. Permisos para Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 8. Instalamos dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# 9. Configuración del puerto para Render
# Render usa el puerto 10000 por defecto, lo configuramos en PHP-FPM
ENV PORT=10000
RUN sed -i "s|listen = .*|listen = 0.0.0.0:${PORT}|" /usr/local/etc/php-fpm.d/www.conf

# 10. Exponemos el puerto
EXPOSE 10000

# 11. Comando de inicio
# Ejecuta migraciones automáticamente y arranca el servidor
CMD php artisan migrate --force && php-fpm
