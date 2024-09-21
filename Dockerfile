FROM  php:8.1-apache
LABEL authors="Mahmoud_elzanklony"

WORKDIR /var/www/microservices-api

# Install system dependencies
# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim unzip git curl \
    && docker-php-ext-install pdo_mysql
# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents to the working directory
COPY . /var/www/microservices-api

# Set file permissions
RUN chown -R www-data:www-data /var/www/microservices-api

# Expose port 80 to the Docker network
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
