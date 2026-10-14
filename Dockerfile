# استخدام صورة PHP رسمية مع الحزم المطلوبة
FROM php:8.3-fpm

# تثبيت متطلبات النظام الأساسية
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

# تنظيف التخزين المؤقت لـ apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# تثبيت ملحقات PHP المطلوبة لاراول
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تحديد مجلد العمل
WORKDIR /var/www/html

# نسخ ملفات Composer أولاً لتحسين التخزين المؤقت
COPY composer.json composer.lock ./

# تثبيت الحزم بدون حزم التطوير (Production)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# نسخ باقي ملفات المشروع
COPY . .

# إنشاء مجلدات التخزين و الـ Cache وضبط الصلاحيات تلقائياً لمنع أي أخطاء
RUN mkdir -p /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/framework/cache \
    /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# تشغيل سكربتات ما بعد التحميل
RUN composer run-script post-autoload-dump || true

# إعطاء صلاحيات الكتابة الكاملة للمجلدات الحيوية
RUN chmod -R 777 storage bootstrap/cache

# منفذ التشغيل
EXPOSE 10000

# إنشاء ملف SQLite فارغ تلقائياً إذا لم يكن موجوداً قبل التشغيل
CMD touch /var/www/html/database/database.sqlite && \
    php artisan config:clear && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=10000