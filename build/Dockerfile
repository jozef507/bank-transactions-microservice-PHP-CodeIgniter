
# VUT FIT - PIS 2021
# Tím - PIS2021
# Projekt - Banka (zadanie z AIS)

# Vypracoval - Marek Šalgovic (xsalgo00)



FROM php:7-apache

COPY . .

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

EXPOSE 5001

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer install

RUN composer dump-autoload

CMD ["php", "-S", "0.0.0.0:5001"]
