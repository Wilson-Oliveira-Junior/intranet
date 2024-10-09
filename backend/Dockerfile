# Laravel Dockerfile
FROM php:8.2-fpm

# Instala as dependências do sistema
RUN apt-get update && apt-get install -y libzip-dev unzip

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www

# Copia os arquivos do projeto para o contêiner
COPY . .

# Instala as dependências do Laravel
RUN composer install --no-interaction --prefer-dist

# Ajusta permissões
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www/storage

# Expõe a porta que o PHP-FPM irá escutar
EXPOSE 9000

# Comando padrão para iniciar o PHP-FPM
CMD ["php-fpm"]
