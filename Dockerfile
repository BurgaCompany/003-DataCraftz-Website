# Gunakan image PHP 8.1 dengan FPM (FastCGI Process Manager)
FROM php:8.1-fpm

# Update package list dan install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libxml2-dev \
    libxslt1-dev \
    libicu-dev \
    libonig-dev \
    g++

# Membersihkan cache apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Instal Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Menambahkan user untuk aplikasi Laravel (ganti User8 dengan www)
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www User8

# Mengatur direktori kerja
WORKDIR /var/www

# Menyalin isi direktori aplikasi
COPY . /var/www

# Menetapkan izin untuk direktori Laravel
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache
RUN chown -R User8:www /var/www

# Mengubah pengguna saat ini ke User8
USER User8

# Menyalin file composer.lock dan composer.json, dan menginstal dependensi PHP
#COPY --chown=User8:www composer.lock composer.json /var/www/
#RUN composer install

# Mengekspos port 9000 dan menjalankan server php-fpm
EXPOSE 9000
CMD ["php-fpm"]
