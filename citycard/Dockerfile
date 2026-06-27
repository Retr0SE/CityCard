# Використовуємо офіційний образ PHP з веб-сервером Apache
FROM php:8.2-apache

# Встановлюємо системні залежності та розширення PHP для роботи з PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Налаштовуємо Apache: корінь сайту має бути в папці public (вимога Laravel)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Вмикаємо mod_rewrite для правильної роботи маршрутизатора
RUN a2enmod rewrite

# Копіюємо всі файли твого проєкту в контейнер
COPY . /var/www/html

# Встановлюємо Composer та підтягуємо залежності проєкту
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader --no-dev

# Надаємо веб-серверу права на запис у папки кешу та логів
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache