FROM php:8.4-fpm

# Dependencias del sistema
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
    unzip \
    nodejs \
    npm \
    libreoffice \
    libreoffice-common

# Extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo_mysql pdo_pgsql mbstring exif pcntl bcmath zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www

# Copiar el proyecto completo
COPY . .

# Instalar dependencias de Node y PHP
RUN npm install
RUN composer install --no-dev --optimize-autoloader

# Asignar permisos correctos para Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# --- CONFIGURACIÓN FIJA PARA PRODUCTION EN RAILWAY ---

# Forzamos a PHP-FPM a escuchar directamente en la dirección y puerto 8080 en texto real
RUN sed -i "s|listen = .*|listen = 0.0.0.0:8080|" /usr/local/etc/php-fpm.d/www.conf

# Declaramos el entorno y exponemos el puerto 8080
ENV PORT=8080
EXPOSE 8080

# Comando de inicio nativo para PHP-FPM
CMD ["php-fpm"]