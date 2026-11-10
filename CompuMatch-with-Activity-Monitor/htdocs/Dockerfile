FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite headers

COPY htdocs/ /var/www/html/

RUN sed -ri 's/80/10000/g' /etc/apache2/ports.conf \
    && sed -ri 's/:80>/:10000>/' /etc/apache2/sites-available/000-default.conf

EXPOSE 10000

CMD ["apache2-foreground"]
