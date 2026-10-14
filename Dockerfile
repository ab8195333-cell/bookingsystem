FROM php:8.3-apache

# تثبيت الحزم المطلوبة لنظام لاراول
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip

# تفعيل Apache Rewrite Module لتوجيه الروابط بشكل صحيح
RUN a2enmod rewrite

# تغيير مجلد العرض الافتراضي لـ Apache إلى مجلد public الخاص بلاراول
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
DocumentRoot /var/www/html/public

RUN sed -ri -s 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -s 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# نسخ ملفات المشروع إلى داخل الحاوية
COPY . /var/www/html

# تعيين صلاحيات المجلدات لتجنب مشاكل التخزين المؤقت والكاش
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# تثبيت Composer لتنزيل الحزم
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80