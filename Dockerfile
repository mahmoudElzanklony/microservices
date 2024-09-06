FROM  php:8.1-apache
LABEL authors="Mahmoud_elzanklony"

WORKDIR /var/www/microservices

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

COPY . /var/www/microservices

# Ensure the storage and bootstrap/cache directories are writable
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Install Composer dependencies
RUN composer install --optimize-autoloader --no-dev

# Copy the Apache vhost configuration for Laravel
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Expose port 80 for the web server
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]

ENTRYPOINT ["top", "-b"]
