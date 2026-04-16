FROM php:8.2-apache

RUN apt-get update && apt-get install -y git unzip
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

# Đưa Document Root về thư mục gốc
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Cấu hình Port cho Render
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

WORKDIR /var/www/html/
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html