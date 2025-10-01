**Prueba Técnica - Gestión de Branded Content**

--- 


## Descripción

Esta aplicación interna permite gestionar contenidos de **branded content** que provienen de distintos proveedores y se publican en medios/periódicos.

Permite:

- Registrar proveedores y medios.

- Gestionar tarifas por medio y proveedor.

- Crear y listar contenidos, incluyendo enlaces asociados.

- Marcar el pago de proveedores.

- Consultar informes por medio y por proveedor.

Tecnologías:

- Backend: Symfony, PHP, MySQL 

- Frontend: Angular, Bootstrap 5

- Autenticación: JWT

- Contenedores opcionales: Docker

---

Requisitos previos:

- PHP 8.2+

- Composer

- Node.js 18+ y npm

- MySQL 8.0+

- Opcional: Docker y Docker Compose

---

# Levantar Servicio sin docker
Vamos a indicar, como levantar primero todo el servicio sin usar docker, después con él. 

- Instalar LAMP(Linux, Apache, Mysql, PHP)
Lo primero es tener instalado las herramientas necesarias para arrancar el servicio. Para que sea más sencillo, es recomendable instalar todo junto a través de LAMP.

```bash
#Actualizamos los paquetes 
sudo apt update
sudo apt upgrade -y
```

- Instalamos en un solo comando **PHP, MYSQL y APACHE**
```bash
sudo apt install apache2 mysql-server php php-mysql libapache2-mod-php php-cli -y
```

- Habilitamos los servicios de **MYSQL** y **APACHE**
```bash
sudo systemctl enable apache2
sudo systemctl start apache2
sudo systemctl enable mysql
sudo systemctl start mysql
```

- Creamos el usuario y la contraseña para la base de datos
```bash
sudo mysql
CREATE USER 'usuario'@'localhost' IDENTIFIED BY 'password';
FLUSH PRIVILEGES;
EXIT;
```

---

### Composer y Symfony Cli

***COMPOSER (OBLIGATORIO)***

- Actualizamos el caché del administrador de paquetes e instalamos las dependencias requeridas
```bash
sudo apt update
sudo apt install php-cli unzip
```

- Descargamos e instalamos composer
```bash
curl -sS https://getcomposer.org/installer | php
```

- Movemos composer a una carpeta que esté accesible para que esté disponible globalmente
```bash
sudo mv composer.phar /usr/local/bin/composer
```

- Verificamos la instalacion
```bash
composer --version
```

***SYMFONY CLI (OPCIONAL)***

Symfony CLI es una herramienta opcional pero muy útil para trabajar con proyectos Symfony. Permite crear proyectos, levantar servidores locales, ejecutar comandos de consola y más.

- Descargamos e instalamos Symfony CLI usando curl
```bash
curl -sS https://get.symfony.com/cli/installer | bash
```

- Movemos el binario a un directorio en tu PATH para que sea accesible desde cualquier terminal
```bash
sudo mv ~/.symfony/bin/symfony /usr/local/bin/symfony
```

- Verificamos que la instalación se ha realizado correctamente
```bash
symfony -v
```

***Angular y Bootstrap***

Angular requiere Node.js y npm (el gestor de paquetes de Node). Instálalos desde la terminal:

```bash
# Actualizamos los repositorios
sudo apt update

# Instalamos Node.js y npm
sudo apt install nodejs npm
```

- Verificamos la instalacion

```bash
node -v
npm -v
```

#### Angular CLI
Angular CLI es la herramienta oficial para crear, construir y ejecutar proyectos Angular.

- Instalamos Angular CLI
```bash
sudo npm install -g @angular/cli
```

- Verificamos la instalacion
```bash
ng version
```

- Crear un proyecto en Angular
```bash
ng new nombre_proyecto
cd nombre_proyecto
```

- Levantar el servidor de desarrollo
```bash
#Por defecto, el proyecto se levantará en http://localhost:4200.
ng serve
```

### Bootstrap

Está diseñado para facilitar el proceso de desarrollo de los sitios web responsivos y orientados a los dispositivos móviles. 

- Instalamos Bootstrap
```bash
  npm install bootstrap
```

- Inclúyelo en angular.json en el apartado de **styles**:
```bash
"styles": [
  "node_modules/bootstrap/dist/css/bootstrap.min.css",
  "src/styles.css"
],
```
 --- 

 
# Levantar Servicio con docker
Si quieres levantar la aplicación con contenedores, necesitarás Docker y Docker Compose instalados.  
Sigue la guía oficial de Docker para Ubuntu: [Instalar Docker Engine](https://docs.docker.com/engine/install/ubuntu/)

Una vez está instalado, podemos continuar:

- Creamos una carpeta donde meteremos el **docker-compose.yaml**
```bash
mkdir (nombre de la carpeta)
```

- Creamos un archivo con extension **.yaml** llamado **docker-compose**. Puedes crearlo desde el terminal o manualmente. Dentro introducimos:

```
#El proyecto incluye un archivo `docker-compose.yml` para levantar la aplicación completa (backend, base de datos, etc.). Para arrancar los servicios:
docker-compose up -d
```

- Para detener los servicios
```bash
docker-compose down
```

el comando **docker-compose up -d** levantará la base de datos MySQL, el servidor web y cualquier otro contenedor necesario para que la aplicación funcione.

---

# Proyecto
Una vez ya tenemos todos los servicios instalados y funcionando, con o sin docker, vamos de lleno al proyecto de symfony.

### Descargar el proyecto

Tenemos 2 formas de descargar el proyecto.
- Git clone.
```bash
#instalar git primero, si no lo tienes en linux
apt-get install git

#clonar el repositorio en la carpeta que quieras
git clone https://github.com/CristianRMN/prueba-tecnica-openads
```

- Descargar el .zip del proyecto desde gitHub.
<img width="495" height="425" alt="zip" src="https://github.com/user-attachments/assets/f4f3de66-15b4-4b0a-8adf-0c830cc1e48d" />

- Abrimos el proyecto (Recomensable usar Visual Studio Code)
Sigue la guía para instalar Visual Studio: [guia oficial de instalacion de visual studio code](https://code.visualstudio.com/docs/setup/linux)

- Nos vamos al terminal de nuestro IDE
```bash
composer install
```
Esto instalará todas las dependencias necesarias de Symfony.

- Necesitamos una serie de archivos (Que por razones de seguridad), no se suben a git.

```bash
#El .env
Este archivo es esencial, ya que tiene todas las contraseñas y variables seguras de nuestro proyecto
```

- Crear el .env
```bash
#mkdir .env
```

- Contenido del archivo **.env**
```bash
###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=
###< symfony/framework-bundle ###

###> symfony/routing ###
# Configure how to generate URLs in non-HTTP contexts, such as CLI commands.
# See https://symfony.com/doc/current/routing.html#generating-urls-in-commands
DEFAULT_URI=http://localhost
###< symfony/routing ###


###> doctrine/doctrine-bundle ###
DATABASE_URL=(tu_ruta_db)
###< doctrine/doctrine-bundle ###

###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=(tu_clave_secreta_JWT)
###< lexik/jwt-authentication-bundle ###
```

Lo que necesitamos cambiar es **DATABASE_URL**.

```bash
#Ejemplo de ruta de base de datos
DATABASE_URL="mysql://(usuario):(contraseña)@localhost:3307/(nombre de la BD)?serverVersion=mariadb-11.3.2&charset=utf8mb4"
```

- Crear la base de datos
Primero nos vamos a posicionar otra vez en el terminal, en la ruta principal del proyecto y hacemos:

```bash
#ejecutamos
php bin/console doctrine:database:create
```

Se introducirá el nombre de la base de datos que hayamos puesto en el archivo de configuracion **.env**.

- Ejecutamos las migraciones
```bash
php bin/console doctrine:migrations:migrate
```

No hace falta hacer:
```bash
php bin/console make:migration
```

Porque al descargar el proyecto, descargamos también el paquete donde estaban todas las migraciones. Solo debemos de importarlas a la nueva **BD** que hemos creado.

- cargar los datos de prueba
```bash
php bin/console doctrine:fixtures:load --append
```

Se cargarán los datos:
```bash
admin@openads.local / admin
```

Con esto, nos desentendemos de tener que registrar manualmente a un usuario a través de un **EndPoint** y solo nos encargamos del inicio de sesión.

--- 

# Autenticacion

Ahora tenemos que hacer un par de ajustes, Las rutas están protegidas, ya que esto es una **API REST** interna y debe de estar protegida por los usuarios **ROLE_ADMIN**

### Como establecer la configuracion

Ejecutamos el siguiente comando:
```bash
#clave privada de nuestro JWT
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
```

Ahora la clave publica:
```bash
#Os pediran la contraseña de la clave privada
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

Importante haber creado previamente los archivos **.pem**, de lo contrario, dará error:
```bash
mkdir config/jwt/private.pem
mkdir config/jwt/public.pem
```

- Poner la clave de la **contraseña privada** en el **.env**.
```bash
JWT_PASSPHRASE=(tu_clave_secreta_JWT)
```

En JWT con Symfony (y en general con cualquier sistema de autenticación basada en tokens):

- Clave privada (private.pem) → se usa para firmar el token en el backend. Solo el servidor la conoce.
- Clave pública (public.pem) → se usa para verificar la firma del token. Cualquier parte que reciba el token (por ejemplo, otro servicio) puede comprobar que es válido y no ha sido alterado.

---

# Iniciamos el servidor
- normalmente correrá en **http://localhost:8000**
```bash
#comando para iniciar el servidor
symfony serve
```

## 📌 Colección Postman/Insomnia

En la carpeta `/postman` encontrarás el archivo JSON con todos los endpoints listos para importar en Postman o Insomnia.

- [Cómo importar en Postman](https://learning.postman.com/docs/getting-started/importing-and-exporting-data/)
- [Cómo importar en Insomnia](https://docs.insomnia.rest/insomnia/import-export-data)
