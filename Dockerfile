FROM php:8.4-fpm

# 1. Instalamos dependencias del sistema (+ Node.js + LibreOffice + NGINX)
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
    libreoffice-common \
    nginx

# 2. Limpieza de caché de paquetes
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Instalamos extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo_mysql pdo_pgsql mbstring exif pcntl bcmath zip

# 4. Traemos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Directorio de trabajo
WORKDIR /var/www

# 6. Copiamos los archivos del proyecto
COPY . .

# 7. Instalar dependencias npm (incluye Carbone)
RUN npm install

# 8. Permisos para Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 9. Instalamos dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# 10. Configuración express de Nginx interno para Railway
RUN echo 'server { \
    listen 10000; \
    root /var/www/public; \
    index index.php index.html; \
    location / { try_files $uri $uri/ /index.php?$query_string; } \
    location ~ \.php$ { \
        include fastcgi_params; \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
    } \
}' > /etc/nginx/sites-available/default

RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# 11. Exponemos el puerto de Railway
EXPOSE 10000

# 12. Comando de inicio (Migra, enciende FPM en segundo plano y arranca Nginx)
CMD php artisan migrate --force && php-fpm -D && nginx -g "daemon off;"