FROM php:8.3-fpm

# 1. تثبيت حزم النظام وامتدادات قواعد البيانات
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

# 3. تحديد مجلد العمل
WORKDIR /var/www/html

# 4. إنشاء مجلدات التخزين والكاش وإعطائها الصلاحيات أولاً
RUN mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# 5. نسخ ملفات الـ composer وتثبيت الحزم بدون سكريبتات تلقائية تمنع البناء
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 6. نسخ باقي ملفات المشروع بالكامل
COPY . .

# 7. إعادة ضبط الصلاحيات للملفات المنسوخة وتوليد الـ Autoloader بأمان
RUN chmod -R 777 storage bootstrap/cache \
    && composer dump-autoload --optimize --no-scripts

# 8. أمر التشغيل المباشر وتنفيذ الـ Migrations ثم تشغيل السيرفر
CMD php artisan migrate --force \
    && php artisan config:clear \
    && php artisan cache:clear \
    && php artisan serve --host=0.0.0.0 --port=10000