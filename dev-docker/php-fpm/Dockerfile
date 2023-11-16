FROM webdevops/php-nginx-dev:8.2
RUN echo date.timezone = "Europe/Budapest" >> /opt/docker/etc/php/php.ini

WORKDIR "/app"
COPY . /app


RUN chown -R application:application /app
