FROM joomla:3.9.16-php7.4-apache
RUN pecl install xdebug-2.9.1
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/php.ini