
# Prueba Despliegue

Este proyecto trata de enseñar como hacer una aplicacion simple con 3 servicios en docker. Para el balanceo de carga utilizamos una imagen oficial de nginx (nginx:alpine). Nuestra aplicación consta de 3 servicios principales:

- Servicio Chiquito: se encarga de generar un chiste aleatorio cada vez que entramos en la pagina
- Servicio Linares (Balanceado): Este servicio pregunta sobre la independencia de linares en jaen y hace un conteo de votos totales persistidos en base de datos
- Servicio de base de datos: Este servicio junto a su repositorio tiene como funcion persistir los datos de la base de datos

## Despliegue inicial de nuestro programa
Para el despliegue de nuestra aplicacion aparte de utilizar nginx como he mencionado antes vamos a utilizar Docker para el levantamiento de los 3 servicios. En este caso podemos encontrar el dockerfile especifico dentro de nuestro repositorio junto a cada uno de los index php.

### paso 1: Ejecución de docker-compose y creación de imágenes

Para empezar a desplegar nuestra aplicacion tendremos que dirigirnos a la carpeta raiz del proyecto donde se encuentra nuestro "docker-compose.yml".

Para ello utilizamos el siguiente comando de Docker

`docker-compose build`

Una vez ejecutado este compose se crearan todas las imagenes contenidas en el. En este caso se han creado las imagenes de Nginx, Mariadb, Chiquito(Apache & php) y Linares(Apache & php). En el caso de linares tenemos el contenedor replicado 3 veces para el balanceo de carga de peticiones

### paso 2: Levantamiento de los contenedores

Para levantar los contenedores usando las imagenes del docker compose vamos a utilizar directamente el siguiente comando:

`docker-compose up -d`

Dicho comando levantara un contenedor para cada una de las imagenes creadas en el docker-compose y acto seguido dejara funcionando en background (de ahi la flag -d) el contenedor para su uso.

Una vez realizados estos pasos nuestra pagina ya esta funcionando pero faltan todavia dos detalles para su funcionamiento completo.

### paso 3: Añadimos nuestros host virtuales al archivo hosts de nuestro pc

Una vez creados nuestro contenedores como podemos ver en nuestra configuracion de nginx hemos definido 2 dominios para acceder a los servicios Chiquito y Linares(balanceado)

```
    server {
       listen 80;
       server_name www.freedomforLinares.com; // <== AQUI

       location / {
           proxy_pass http://site1-servers;
        }

    }

    server {
        listen 80;
        server_name www.chiquito.com; // <== AQUI

        location / {
            proxy_pass http://site2;
        }

    }
```
Para que dichos dominios funcionen correctamente tendremos que ir a nuestro archivo hosts y añadirlos para que el navegador haga un puente entre nuestra ip y el nombre de domimio

para ello haremos lo siguiente:

- Abrimos nuestra terminal y escribimos el siguiente comando:
    ` sudo nano /private/etc/hosts`

Una vez dentro de este archivo nos encontraremos una estructura similar a la siguiente:

```
# Copyright (c) 1993-2009 Microsoft Corp.
#
# This is a sample HOSTS file used by Microsoft TCP/IP for Windows.
#
# This file contains the mappings of IP addresses to host names. Each
# entry should be kept on an individual line. The IP address should
# be placed in the first column followed by the corresponding host name.
# The IP address and the host name should be separated by at least one
# space.
#
# Additionally, comments (such as these) may be inserted on individual
# lines or following the machine name denoted by a '#' symbol.
#
# For example:
#
#      102.54.94.97     rhino.acme.com          # source server
#       38.25.63.10     x.acme.com              # x client host

# localhost name resolution is handled within DNS itself.
#	127.0.0.1       localhost
#	::1             localhost
# Added by Docker Desktop
192.168.122.145 host.docker.internal
192.168.122.145 gateway.docker.internal
# To allow the same kube context to work on the host and the container:
127.0.0.1 kubernetes.docker.internal
# End of section

127.0.0.1 www.chiquito.com // AQUI tenemos que añadir los dominios 
127.0.0.1 www.freedomforLinares.com // para que nuestra pagina funcione
```

Una vez modificado el archivo haremos `cmmd + o` luego haremos `Enter` y luego `cmmd + x` y nuestro archivo quedara modificado y guardado con los dos dominios añadidos.

### paso 4: configuracion de la base de datos

Una vez creados nuestros contenedores y con los dos dominios funcionando ahora procederemos a modificar la base de datos.

para ello nos iremos a la terminal de nuestro dispositivo e introduciremos el siguiente comando
para acceder a la terminal bash de nuestro contenedor:

`docker exec -it db1 bash`  // En este caso db1 porque es el nombre del contenedor db

una vez dentro introduciremos el siguiente comando:

`mysql -u root -proot` 

En este caso dependera de si hemos configurado algun tipo de contraseña especifica pero el estandard en este proyecyo es -u root -p root

Una vez introducido este comando tendremos acceso a la base de datos y podremos manipular/crear tablas y bases de datos de acorde al proyecto.

En este caso hemos creado la tabla votos para almacenar la totalidad de los mismos

```
CREATE TABLE votos (
    votos_si INT,
    votos_no INT
);
```

---

Una vez realizados todos los pasos previos tendremos acceso finalmente a nuestra pagina utilizando los dominios en un navegador.

Disclaimer 
=
En caso de haber modificado el puerto original de nginx (como es nuestro caso al haber usado el puerto 8090) a la hora de introducir cualquiera de los dominios tendremos que tenerlo en cuentra e introducirlo de la siguiente manera:

```
www.chiquito.com:8090
www.freedomforLinares.com:8090
```

Si hemos seguido cada paso de la guía con cautela al introducir los dominios deberia de aparecer nuestra pagina web cargando tanto los votos en Linares como generando chistes en Chiquito



## Authors

- [@miwDev](https://github.com/miwDev)

