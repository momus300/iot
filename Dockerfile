FROM webdevops/php-apache-dev:7.1
ADD . /app
WORKDIR /app
RUN apt-get update
RUN apt-get install -y nano
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN pecl install mongodb
COPY ./conf/php.ini /opt/docker/etc/php/php.ini
RUN composer require mongodb/mongodb

#COPY conf/momusSite.crt /opt/docker/etc/httpd/ssl/server.crt
#COPY conf/momusSite.key /opt/docker/etc/httpd/ssl/server.key
#COPY conf/666-momus-ovh.conf /opt/docker/etc/httpd/vhost.common.d/