# Usar una imagen base de PHP con Apache
FROM php:8.2-apache

# Instalar extensiones de PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar el módulo de Apache para reescribir URLs (opcional)
RUN a2enmod rewrite

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Comando por defecto para iniciar Apache
CMD ["apache2-foreground"]