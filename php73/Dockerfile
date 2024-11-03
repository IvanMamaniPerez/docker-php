FROM php:7.3.33-fpm-alpine3.14

# Instalar dependencias y herramientas de compilación
RUN apk update && apk add --no-cache \
    unixodbc-dev \
    curl-dev \
    php7-pear \
    php7-openssl \
    php7-pdo \
    php7-dev \
    autoconf \
    g++ \
    make \
    gnupg  \
    nginx

ADD https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/msodbcsql17_17.10.5.1-1_amd64.apk ./msodbcsql17_17.10.5.1-1_amd64.apk
ADD https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/mssql-tools_17.10.1.1-1_amd64.apk ./mssql-tools_17.10.1.1-1_amd64.apk

# Verify signature, if 'gpg' is missing install it using 'apk add gnupg':
ADD https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/msodbcsql17_17.10.5.1-1_amd64.sig ./msodbcsql17_17.10.5.1-1_amd64.sig
ADD https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/mssql-tools_17.10.1.1-1_amd64.sig ./mssql-tools_17.10.1.1-1_amd64.sig



RUN curl https://packages.microsoft.com/keys/microsoft.asc  | gpg --import -
RUN gpg --verify msodbcsql17_17.10.5.1-1_amd64.sig msodbcsql17_17.10.5.1-1_amd64.apk
RUN gpg --verify mssql-tools_17.10.1.1-1_amd64.sig mssql-tools_17.10.1.1-1_amd64.apk

#Install the package(s)
RUN apk add --allow-untrusted msodbcsql17_17.10.5.1-1_amd64.apk
RUN apk add --allow-untrusted mssql-tools_17.10.1.1-1_amd64.apk

RUN  pecl install sqlsrv-5.9.0
RUN  pecl install pdo_sqlsrv-5.9.0
# RUN sudo su
RUN echo extension=pdo_sqlsrv.so >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/10_pdo_sqlsrv.ini
RUN echo extension=sqlsrv.so >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/20_sqlsrv.ini

# Limpiar las herramientas de desarrollo
RUN apk del php7-dev g++ make

# Copiar el archivo de configuración de Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Crear el directorio para los archivos web
WORKDIR /var/www/html

COPY ./index.php .
COPY ./connection.php .
# Configura permisos para el usuario nginx
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Iniciar tanto php-fpm como Nginx
CMD ["/bin/sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
