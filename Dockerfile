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

# إنشاء ملف إعدادات مخصص لـ Apache لتوجيه المسار إلى مجلد public
RUN echo '<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# نسخ ملفات المشروع إلى داخل الحاوية
COPY . /var/www/html

# تعيين صلاحيات المجلدات لتجنب مشاكل التخزين المؤقت والكاش
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# تثبيت Composer لتنزيل الحزم
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# إنشاء سكريبت تشغيل يقوم بإنشاء قاعدة SQLite، تشغيل الـ Migrations، ثم تشغيل Apache
RUN echo '#!/bin/sh\n\
mkdir -p /var/www/html/database\n\
if [ ! -f /var/www/html/database/database.sqlite ]; then\n\
    touch /var/www/html/database/database.sqlite\n\
fi\n\
chown -R www-data:www-data /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache\n\
php artisan migrate --force\n\
apache2-foreground' > /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

CMD ["/usr/local/bin/entrypoint.sh"]

EXPOSE 80