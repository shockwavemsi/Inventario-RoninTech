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

# Copiar proyecto
COPY . .

# Instalar dependencias
RUN npm install
RUN composer install --no-dev --optimize-autoloader

# Permisos
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Railway: PHP-FPM debe escuchar en $PORT
ENV PORT=10000
RUN sed -i "s|listen = .*|listen = ${PORT}|" /usr/local/etc/php-fpm.d/www.conf

# Exponer puertos
EXPOSE 9000
EXPOSE 10000

# Comando de inicio
CMD php-fpm