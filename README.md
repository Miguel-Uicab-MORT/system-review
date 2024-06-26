# Instrucciones para iniciar el proyecto Laravel Sail con Vue.js

Este repositorio contiene un proyecto desarrollado con Laravel Sail y Vue.js. Sigue las siguientes instrucciones para ejecutar el proyecto en tu máquina local.

Requisitos previos:

- Docker Desktop instalado en tu sistema.

Pasos para iniciar el proyecto:

1. Inicia Docker Desktop en tu sistema si aún no lo has hecho.

2. Abre la terminal de Ubuntu.

3. Clona este repositorio en tu máquina local:

   git clone https://github.com/Miguel-Uicab-MORT/system-review.git

4. Entra al directorio del proyecto:

   cd tu_proyecto

5. Copia el archivo .env.example y renómbralo como .env:

   cp .env.example .env

6. Instala las dependencias de PHP usando Composer:

   composer install

7. Inicia los contenedores de Docker utilizando Laravel Sail:

   ./vendor/bin/sail up -d

8. Ejecuta las migraciones de la base de datos:

   ./vendor/bin/sail artisan migrate

9. Instala las dependencias de JavaScript usando npm:

   ./vendor/bin/sail npm install

10. Compila los assets de Vue.js para desarrollo:

    ./vendor/bin/sail npm run dev

¡Y eso es todo! Ahora puedes acceder a tu aplicación Laravel Sail con Vue.js desde tu navegador web visitando http://localhost. Si necesitas detener los contenedores de Docker, ejecuta:

./vendor/bin/sail down

¡Disfruta desarrollando tu proyecto!
