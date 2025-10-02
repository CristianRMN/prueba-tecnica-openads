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

# Levantar servicios con **Docker**

Me ha sido muy dificil, dado el poco tiempo que he tenido, pero estos son los pasos que hice para que me funcionases los endPoints en docker.

1. descargamos el proyecto

Nos descargamos el proyecto de git hub, ya sea clonandolo o con el .zip, da igual

2. Colocamos el proyecto (IMPORTANTE SIN RENOMBRAR LA CARPETA), en donde queramos, junto con estos archivos/carpetas

- docker/apache
```bash
Lo encontraréis dentro del proyecto que os descargasteis, tiene una configuracion apache que vamos a necesitar luego al montar el docker
```

- docker-compose.yml
```bash
Lo encontrareis tambien en el proyecto, lo dejais junto al proyecto descargado y el **docker/apache**
```

<img width="789" height="265" alt="jejejejenrjfnre" src="https://github.com/user-attachments/assets/785ec377-3a7c-472b-a0bc-d755a60b4bcb" />


Dejalo tal cual, no alteres nada y si lo haces, por ejemplo, modificando usuario o contraseña de la **url de la BD**, acuérdate de hacerlo igual en el docker compose.

### Siguiente paso

Lo primero es asegurarte de tener instalado docker.
Una vez lo teneis, corremos el **contenedor docker-compose.yml**

- Nos metemos dentro del contenedor ***symfony app***
```bash
docker exec -it symfony_app bash
```

- Dentro ejecutamos esto.
```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer
composer --version
```

Son comandos para:
1. Descargar composer
2. Posicionar composer en un lugar que sea accesible y poder acceder globalmente
3. Comprobar que esté instalado


### Instalar composer
- Necesitamos instalar composer, pese a que está en el proyecto.
```bash
#comando necesario para instalar composer
apt update && apt install -y unzip zip git
```

- Luego ejecutamos
```bash
composer install
```

Si hay algún error, seguramente sea el .env que no lo habeis copiado correctamente.

- limpiamos la base de datos

```bash
#salimos del contenedor de symfony
exit
#Entramos en el de mysql
docker exec -it mysql_db mysql -u root -p
```

Nos pedirá contraseña e introducimos root.

```bash
#ejecutamos lo siguiente
DROP DATABASE openads;
```

### Migraciones
- Volvemos a entrar en el contenedor de symfony
```bash

#comando para crear la base de datos desde symfony
php bin/console doctrine:database:create

#comando para importar los esquemas de las tablas a symfony (Si os da error o tarda, probad de nuevo)
php bin/console doctrine:schema:create

#Comando para migrar el usuario y contraseña a la tabla user 
php bin/console doctrine:fixtures:load
```


## 📌 Colección Postman/Insomnia

En la carpeta `/postman` encontrarás el archivo JSON con todos los endpoints listos para importar en Postman o Insomnia.
El unico que no va a funcionar es el de Login, por fallos que me dio y el poco tiempo que se me proporcionó.

- [Cómo importar en Postman](https://learning.postman.com/docs/getting-started/importing-and-exporting-data/)
- [Cómo importar en Insomnia](https://docs.insomnia.rest/insomnia/import-export-data)

## Ejemplos de endPoints

- Como autenticarse

```bash
POST http://localhost:8000/login
Body:
{
  "username": "admin@openads.local",
  "password": "admin"
}
```

## Diagrama de entidades del proyecto

```bash
Proveedor ───< Tarifa >─── Medio
   │
   └──< Contenido >──< Enlace

```

🚀 **Bonus / Futuro trabajo**

Este proyecto se ha desarrollado en un plazo muy limitado de 48 horas, por lo que algunas mejoras no se han podido implementar todavía.

1. Frontend en Angular

El enunciado pedía un frontend en **Angular + Bootstrap 5**.
Actualmente el backend está funcional con JWT, pero:

- No dispongo de experiencia previa con Angular.

En dos días **no es viable** implementar una aplicación completa para un proyecto de este tamaño.

👉 Dado más tiempo, implementaría un frontend con las vistas mínimas:

- Login (con **JWT**).

- CRUD de proveedores, medios, tarifas y contenidos.

- Informes con tablas filtrables.

2. Tests automáticos

- Incluir tests unitarios y de integración con **PHPUnit** para validar los servicios y controladores.

- Añadir tests funcionales para los endpoints clave (login, CRUDs, informes).

3. Seeds adicionales

- Actualmente se cargan datos mínimos (usuario admin).

- Mejorar con seeds de proveedores, medios, tarifas y contenidos de ejemplo para poder probar la app de forma inmediata.

4. Corrección de bugs y validaciones extra

En un plazo tan ajustado siempre pueden quedar errores o casos límite no contemplados.
Añadir validaciones más estrictas en campos críticos (emails válidos, URLs que respondan 200 OK, normalización de dominios, etc.).
