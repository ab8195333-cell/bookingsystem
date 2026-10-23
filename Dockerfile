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

# 4. صلاحيات المجلدات وإنشاء ملف SQLite
RUN mkdir -p storage storage/framework/views storage/framework/sessions storage/framework/cache bootstrap/cache database \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database \
    && chmod 777 database/database.sqlite

# 5. تثبيت الاعتمادات
RUN composer install --no-dev --optimize-autoloader

# 6. التشغيل: إنشاء المفتاح إذا لم يوجد، تنفيذ التهجير، ثم تشغيل الخادم
CMD php -r "file_exists('.env') || copy('.env.example', '.env');" \
    && php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');" \
    && php artisan key:generate --force \
    && php artisan migrate --force \
    && php artisan config:clear \
    && php artisan route:clear \
    && php artisan serve --host=0.0.0.0 --port=10000