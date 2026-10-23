FROM php:8.3-fpm

# 1. تثبيت متطلبات النظام وامتدادات قواعد البيانات
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

# 4. صلاحيات المجلدات وإنشاء ملف SQLite الأساسي
RUN mkdir -p storage bootstrap/cache database \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database \
    && chmod 777 database/database.sqlite

# 5. تثبيت الاعتمادات
RUN composer install --no-dev --optimize-autoloader

# 6. الترتيب الصحيح عند التشغيل: إنشاء الملف، تشغيل المايجريشن أولاً، ثم مسح الكاش وتشغيل السيرفر
CMD php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');" \
    && php artisan migrate --force \
    && php artisan optimize:clear \
    && composer dump-autoload --optimize \
    && php artisan serve --host=0.0.0.0 --port=10000