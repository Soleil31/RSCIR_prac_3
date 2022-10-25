FROM php:7.2-apache

RUN apt-get update -y && apt-get install -y && docker-php-ext-install mysqli && apt-get install -y libaprutil1-dbd-mysql
RUN a2enmod authn_dbd