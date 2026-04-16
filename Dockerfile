FROM php:8.2-apache [cite: 1]

RUN apt-get update && apt-get install -y git unzip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

# CHỈNH SỬA: Đưa Document Root về thư mục gốc /var/www/html
ENV APACHE_DOCUMENT_ROOT /var/www/html [cite: 1]
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf 
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf 

RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf 

# Cấu hình Port cho Render
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf 

WORKDIR /var/www/html/ 
COPY . /var/www/html/ [cite: 2]

# Nếu bạn không dùng Composer, hãy thêm dấu # vào trước dòng dưới đây
RUN composer install