FROM php:8.3-fpm

# تثبيت متطلبات النظام وامتدادات قواعد البيانات (بما فيها PostgreSQL)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql pdo_pgsql pgsql gd

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# تأكد من إنشاء المجلدات ومنح الصلاحيات اللازمة قبل تشغيل composer install
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000
CMD php artisan serve --host=0.0.0.0 --port=10000