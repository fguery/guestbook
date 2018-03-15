FROM  php:7.2-apache

RUN apt-get update \
  && apt-get -y install libpq-dev ${PHPIZE_DEPS} \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && echo " xdebug.remote_enable=on \n\
            xdebug.remote_host=docker.for.mac.localhost\n\
            xdebug.remote_handler=\"dbgp\" \n\
            xdebug.remote_mode=\"req\" \n\
            xdebug.remote_port=9000 \n\
            xdebug.remote_connect_back=0\n\
            xdebug.idekey=\"phpstorm\" \n\
            xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && docker-php-ext-install pdo pdo_pgsql \
  && apt-get -y remove libpq-dev ${PHPIZE_DEPS}

RUN a2enmod rewrite
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
