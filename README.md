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

