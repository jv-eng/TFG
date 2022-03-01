***Autor: Jaime Villanua***

# Sincronizacion-de-un-Sistema-Web-de-Gestion-de-Tutorias-Docentes-con-agendas-online

Con este trabajo se pretende mejorar un Sistema Web para la Gestión de Tutorías Docentes ya desarrollado en dos trabajo previos.
Las mejoras consisten en el arreglo de fallos para algunas funcionalidades de la plataforma que se ven reflejados en una falta de sincronización de los eventos en la agenda on-line (realizada con Google Calendar) existente, así como la mejora del aspecto de la plataforma.

El objetivo principal consiste en la mejora de la experiencia de usuario de un Sistema Web para la Gestión de Tutorías Docentes en la [E.T.S. de Ingenieros Informáticos](https://www.fi.upm.es/), a través de una mejora considerable de la apariencia de la web (Front End). 


## Objetivos

•	Familiarizarse con la herramienta anteriormente desarrollada. 
•	Realizar un estudio de las tecnologías utilizadas.
•	Implementar de forma adecuada las funciones del administrador.
•	Rediseñar la interfaz web de la herramienta. 
•	Pruebas de usabilidad y funcionalidad.
•	Integración de un nuevo calendario en el sistema usando Google Calendar.


## Deployment

Para poder utilizar este sistema se necesita tener [XAMPP](https://www.apachefriends.org/es/index.html) descargado (para poder así ejecutar los servidores de Apache y MySQL esenciales para la ejecución).

### XAMPP

Descargar e instalar [XAMPP] v7.4.15 (https://www.apachefriends.org/es/index.html). 
Despues, iniciar la aplicación de XAMPP y pulsar *start* en Apache y MySQL.
Una vez hecho esto, hay que dirigirse al navegador y navegar por el dashboard del localhost (servidor funcionando): *localhost/dashboard*

Si es la **primera vez que abre en local este sistema** deberá de crear la base de datos (*prueba2_tfg_tutorias*) mediante el fichero *prueba2_tfg_tutorias.sql*
Para esto necesitará utilizar **phpMyAdmin**.

### phpMyAdmin
Antes de crear la base de datos desde el directorio *localhost/dashboard*, hay que asegurarse que se tienen tanto el servidor de Apache como el de MySQL "running" funcionando.
Estos servidores se encienden desde el control panel de XAMPP simplemente pulsando en el boton "start" situado a la derecha de cada uno. 

Ahora, tiene que crear una base de datos (a la izquierda aparece *Nueva* que permite crear una base de datos) que se llame prueba2_tfg_tutorias. Haga click en esta base de datos
que acaba de crear y ahora presione el botón de *Importar* arriba en el centro. Busque el fichero *prueba2_tfg_tutorias.sql* y presione *Continuar* abajo a la derecha.
Tras esto se generará la base de datos correctamente junto con sus tablas. El **Admin** será el primer profesor que se cree en el sistema.

Una vez hecho esto, hay que configurar la agenda online de Google Calendar, instalando Composer (leer a continuación).

### Composer (para Google API)

Para utilizar la API de Google Calendar, hay que instalarse la version 2.0.11 de [Composer] ,la cual se puede descargar desde su página web (https://getcomposer.org/download/).

IMPORTANTE: Para un correcto funcionamiento de la agenda online, es ESTRICTAMENTE NECESARIO utilizar la versión 2.0.11 de Composer.

Ademas, existen varios requisitos que hay que cumplir:

•	 PHP 7.4.15.
•	 Windows y XAMPP v.7.4.15.
•	 Cuenta de Google (preguntar al creador de este repositorio por la cuenta)
•	 Tener los credenciales que otorga Google (preguntar al creador de este repositorio)

Nos metemos en XAMPP, abrimos el **Shell** y nos metemos en el directorio de la carpeta del proyecto.

Luego, nos metemos en la página de [Composer] , buscamos la ultima version e introducimos el comando de instalación, el cual se encuentra en el apartado "Command-line installation" en la pagina de [Composer].
.

Una vez hecho esto, vamos a verificar que se ha instalado la version 2.0.11. Para ello, introducimos el siguiente comando en el shell de Windows (desde el directorio donde se encuentra el proyecto):

```
composer -V
```

Esto debería imprimir lo siguiente:

```
Composer version 2.0.11
```

Luego, creamos *composer.bat* junto a *composer.phar* mediante este código:

```
echo @php "%~dp0composer.phar" %*>composer.bat
```

Teniendo el composer ya instalado y con las credenciales instalamos el **Google Client Library**:

```
composer require google/apiclient:^2.0
composer update --ignore-platform-reqs
```
El último commando actualizará a la última versión de Google Api Client ([última release de la API](https://github.com/googleapis/google-api-php-client/releases)).

Aun así, hay que asegurar que la API de Google Calendar está activada correctamente, metiéndose en [Google API Console](https://console.developers.google.com/) y comprobando en biblioteca si Google API Calendar esta *Enabled*. Si está *Enabled* lo que ocurrirá es que en vez del botón *Enable* ahora aparecerá *Administrar*.

Si hay algún fallo con las credenciales, ir a la sección de credenciales y crear una. Usar opción de **Desktop APP** y llamarla como quiera.

Instalar Carbon:

```
composer require nesbot/carbon
```

Ahora ir a la dirección donde esta el proyecto y ejecutar el siguiente comando:

```
php quickstart.php
```
Con esto nos va a generar un enlace donde habrá que meterse con la cuenta del TFG (preguntar al propietario de la cuenta) para darle permisos de manipulación para los calendarios. Al darle permisos nos dará un código de verificación. Este código lo guardaremos por si acaso y lo pegamos en el terminal donde se ha ejecutado el comando.


## Upload

Para poder enviar cambios y correcciones del sistema se necesita utilizar la herramienta [Git](https://git-scm.com/).

### Git

Para poder subir ficheros se necesita estar en el directorio donde se ha guardado el repositorio. Después, en orden se pondrán los siguientes commandos:

```
git add -A

git commit -m "comentario sobre los cambios o correcciones"

git push -u origin master
```

Tambien se puede utizar GitHub Desktop en caso de no estar familiariado con la consola de Git.
