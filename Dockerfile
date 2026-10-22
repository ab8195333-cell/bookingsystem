FROM php:8.3-fpm

# 1. تثبيت متطلبات النظام وامتدادات قواعد البيانات (PDO MySQL & PostgreSQL & GD)
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

# 2. نسخ Composer من الصورة الرسمية
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. تحديد مجلد العمل ونسخ كافة ملفات المشروع
WORKDIR /var/www/html
COPY . .

# 4. إنشاء مجلدات التخزين والكاش وإعطاؤها الصلاحيات الكاملة
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# 5. تثبيت حزم الاعتمادات بدون مكتبات التطوير
RUN composer install --no-dev --optimize-autoloader

# 6. إعادة توليد الخريطة البرمجية للملفات والكونترولرات لتفادي خطأ Target class not found
RUN composer dump-autoload --optimize

# 7. تنظيف الذاكرة المؤقتة، تنفيذ الهجرات (Migrations)، وتشغيل السيرفر على البورت 10000
CMD php artisan config:clear \
    && php artisan route:clear \
    && php artisan cache:clear \
    && php artisan migrate --force \
    && php artisan serve --host=0.0.0.0 --port=10000

    