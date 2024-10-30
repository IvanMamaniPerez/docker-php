FROM alpine:3.20

EXPOSE 9000

# Actualizar y añadir los paquetes necesarios para PHP 8.3
RUN apk update && \
    apk add --no-cache curl gnupg php83 php83-pdo php83-fpm php83-dev php83-pear php83-openssl unixodbc-dev ca-certificates make gcc g++ && \
    update-ca-certificates

# Instalar las extensiones SQL Server usando pecl
RUN pecl install sqlsrv pdo_sqlsrv && \
    echo "extension=sqlsrv.so" > /etc/php83/conf.d/00_sqlsrv.ini && \
    echo "extension=pdo_sqlsrv.so" > /etc/php83/conf.d/10_pdo_sqlsrv.ini

# Descargar e instalar msodbcsql y mssql-tools
RUN cd /tmp && \
    curl -O https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/msodbcsql17_17.10.2.1-1_amd64.apk && \
    curl -O https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/mssql-tools_17.10.1.1-1_amd64.apk && \
    curl -O https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/msodbcsql17_17.10.2.1-1_amd64.sig && \
    curl -O https://download.microsoft.com/download/e/4/e/e4e67866-dffd-428c-aac7-8d28ddafb39b/mssql-tools_17.10.1.1-1_amd64.sig && \
    curl https://packages.microsoft.com/keys/microsoft.asc | gpg --import - && \
    gpg --verify msodbcsql17_17.10.2.1-1_amd64.sig msodbcsql17_17.10.2.1-1_amd64.apk && \
    gpg --verify mssql-tools_17.10.1.1-1_amd64.sig mssql-tools_17.10.1.1-1_amd64.apk && \
    apk add --allow-untrusted msodbcsql17_17.10.2.1-1_amd64.apk && \
    apk add --allow-untrusted mssql-tools_17.10.1.1-1_amd64.apk && \
    rm -r /tmp/*

# Configuración de PHP-FPM
RUN sed -i '/^listen = /c listen = 9000' /etc/php83/php-fpm.d/www.conf && \
    sed -i '/^listen.allowed_clients/c ;listen.allowed_clients' /etc/php83/php-fpm.d/www.conf 

# Ajuste del PATH para herramientas de SQL Server
ENV PATH $PATH:/opt/mssql-tools/bin

CMD ["php-fpm83", "--nodaemonize"]
