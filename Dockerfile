# Gunakan image PHP resmi yang ringan berbasis Alpine Linux
FROM php:8.2-fpm-alpine

# Install system dependencies yang dibutuhkan Nginx dan PostgreSQL driver
RUN apk add --no-cache \
    nginx \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set folder kerja di dalam container
WORKDIR /var/www/html

# Copy composer files dulu (untuk layer caching yang lebih efisien)
COPY composer.json composer.lock ./

# Install dependensi Laravel untuk production
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Salin semua file proyek ke dalam container
COPY . .

# Jalankan composer scripts setelah semua file ada
RUN composer dump-autoload --optimize

# Set permission agar Nginx/Laravel bisa menulis log dan cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Salin konfigurasi Nginx internal
COPY .docker/nginx.conf /etc/nginx/nginx.conf

# Port standar yang wajib dibuka untuk Google Cloud Run
EXPOSE 8080

# Jalankan php-fpm di background (&), lalu nginx sebagai proses utama (foreground)
CMD php artisan migrate --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php-fpm & \
    nginx -g 'daemon off;'
