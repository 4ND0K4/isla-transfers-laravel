# Usamos la imagen de PHP con Apache
FROM php:8.1-apache

# Instalar dependencias de PHP y extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    xml \
    bcmath \
    zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar Node.js y npm para usar Vite
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - && apt-get install -y nodejs

# Configurar Apache
RUN a2enmod rewrite
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copiar el código fuente al contenedor
COPY . /var/www/html

# Configurar permisos para los archivos de la aplicación
RUN chown -R www-data:www-data /var/www/html

# Exponer puerto 80
EXPOSE 80

# Comando por defecto
CMD ["apache2ctl", "-D", "FOREGROUND"]
