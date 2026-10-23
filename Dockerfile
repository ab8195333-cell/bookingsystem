FROM php:8.3-fpm

# 1. تثبيت متطلبات النظام وامتدادات قواعد البيانات (بما فيها pgsql)
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

# 2. نسخ Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. تحديد مجلد العمل ونسخ الملفات
WORKDIR /var/www/html
COPY . .

# 4. صلاحيات المجلدات الأساسية
RUN mkdir -p storage storage/framework/views storage/framework/sessions storage/framework/cache bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# 5. تثبيت الاعتمادات
RUN composer install --no-dev --optimize-autoloader

# 6. التشغيل المباشر وتنفيذ الترحيل لـ Supabase
CMD php artisan migrate --force \
    && php artisan serve --host=0.0.0.0 --port=10000