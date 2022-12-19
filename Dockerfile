FROM php:7.2-apache
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
RUN apt-get update -y && apt-get install -y apache2-utils libaprutil1-dbd-mysql libpng-dev libgmp-dev zip unzip libz-dev libzip-dev
RUN a2enmod authn_dbd
RUN docker-php-ext-install mysqli gd
RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install zlib zip

RUN curl -O https://phar.phpunit.de/phpunit-6.5.0.phar
RUN chmod +x phpunit-6.5.0.phar && mv phpunit-6.5.0.phar /usr/local/bin/phpunit

WORKDIR /var/www/html

#RUN  composer require amenadiel/jpgraph
#RUN  composer require fakerphp/faker
#RUN  composer require --dev phpunit/phpunit
#RUN  composer install
#RUN phpunit --version

#CMD bash -c "composer require amenadiel/jpgraph"
#CMD bash -c "composer require fakerphp/faker"
