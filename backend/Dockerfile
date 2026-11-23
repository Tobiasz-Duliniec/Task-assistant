FROM php:8.2-apache
COPY ./backend/src /var/www/html/
WORKDIR /var/www/html/
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
EXPOSE 80