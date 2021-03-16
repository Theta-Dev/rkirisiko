FROM php:7.4-apache
RUN apt-get update -y && apt-get install -y libpng-dev
RUN docker-php-ext-install mysqli pdo pdo_mysql gd

RUN a2enmod rewrite
RUN a2enmod actions
RUN service apache2 restart