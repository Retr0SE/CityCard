# Використовуємо офіційний образ PHP з веб-сервером Apache
FROM php:8.4-apache

# Встановлюємо системні залежності та купу додаткових бібліотек
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# Налаштовуємо Apache: корінь сайту має бути в папці public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Вмикаємо mod_rewrite для правильної роботи маршрутизатора
RUN a2enmod rewrite

# Копіюємо всі файли проєкту
COPY . /var/www/html

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Встановлюємо пакети БЕЗ виконання скриптів (--no-scripts), щоб уникнути помилок Artisan
RUN composer update --optimize-autoloader --no-dev --no-scripts --prefer-source

# Надаємо веб-серверу права на запис
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache