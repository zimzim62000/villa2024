FROM php:8.2-apache

RUN a2enmod rewrite

RUN a2enmod ssl

ENV TZ="Europe/Paris"

RUN apt update && apt install -y git unzip zip curl nano wget apt-transport-https lsb-release ca-certificates net-tools iputils-ping

RUN apt update && apt install -y nodejs libxml2-dev gnupg gnupg2

RUN apt update && apt install -y npm

RUN curl -sSLo /usr/share/keyrings/deb.sury.org-php.gpg https://packages.sury.org/php/apt.gpg

RUN sh -c 'echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'

RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg

RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt# /sources.list.d/php.list

WORKDIR /var/www/html

COPY . .
COPY server/site.conf /etc/apache2/sites-enabled/site.conf
COPY server/charset.conf /etc/apache2/conf-enabled/charset.conf
COPY server/php.ini /usr/local/etc/php/conf.d/extra-php-config.ini

COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

RUN npm install -g yarn

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions gd pdo_mysql bcmath zip ldap intl opcache ftp
