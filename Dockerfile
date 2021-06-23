FROM quay.io/orvice/apache-base:latest

LABEL MAINTAINER="FAN VINGA<fanalcest@gmail.com> ZE3kr<ze3kr@icloud.com>"

ENV HOST_KEY=e9e4498f0584b7098692512db0c62b48 \
    HOST_MAIL=ze3kr@example.com               \
    TITLE=TlOxygen

COPY . /var/www/html/public

COPY .docker/entrypoint.sh  /usr/bin/entrypoint.sh

RUN  curl -s https://getcomposer.org/installer | php            && \
    php composer.phar install --no-dev -o


WORKDIR /var/www/html/public
EXPOSE 80

RUN cp /var/www/html/public/config.example.php /var/www/html/public/config.php 

ENTRYPOINT [ "/usr/bin/entrypoint.sh" ]