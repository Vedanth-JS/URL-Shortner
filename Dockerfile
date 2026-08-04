FROM php:7.4-alpine3.15

WORKDIR /app
RUN apk add --no-cache nodejs npm libzip-dev libpng-dev python2 make g++ \
    && docker-php-ext-install mysqli pdo pdo_mysql gd bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . ./
RUN composer install
RUN npm config set python python2
RUN npm install
RUN npm run dev
RUN echo -e '#!/bin/sh\nuntil nc -z mysql 3306; do sleep 1; done\nphp artisan migrate -n --force\nif [ ! -f "/seed-done" ]; then php artisan db:seed -n --force && php artisan settings:set && touch /seed-done; fi\nphp artisan serve --host=0.0.0.0 --port=8000' > entrypoint.sh && chmod +x entrypoint.sh
CMD [ "./entrypoint.sh" ]
