FROM php:8.2-apache

# Install required system libraries and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libfreetype6-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        curl \
        gd \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Copy the CompuMatch PHP application
COPY htdocs/ /var/www/html/

# Create config.php from the sample configuration
RUN cp /var/www/html/config.sample.php /var/www/html/config.php

# Set permissions for uploaded files
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 775 /var/www/html/uploads

# Configure Apache for Render's port
COPY docker-entrypoint.sh /usr/local/bin/render-entrypoint
RUN chmod +x /usr/local/bin/render-entrypoint

EXPOSE 10000

ENTRYPOINT ["render-entrypoint"]
CMD ["apache2-foreground"]

