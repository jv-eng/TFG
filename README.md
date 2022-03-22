***Autor: Juan Viejo***

# Sincronizacion-de-un-Sistema-Web-de-Gestion-de-Tutorias-Docentes-con-agendas-online
En las primeras reuniones con el tutor quedó constancia de las múltiples dependencias y problemas a la hora de crear el entorno de desarrollo. A pesar de que el proceso de instalación estaba documentado en un fichero “README.md” creado en los TFG anteriores, durante la creación del entorno de desarrollo se encontró que había algunos pasos e instrucciones que no estaban completamente detallados, por lo que el proceso se complicaba. Además, cabe destacar que las versiones utilizadas en los trabajos anteriores han sido actualizadas, dado que existían dependencias que, con esas versiones, impedían el correcto funcionamiento de la aplicación.
Por todo ello, se ha decidido reescribir este anexo, en el que se detalla tanto las versiones utilizadas como el proceso a seguir para realizar una correcta creación del entorno de desarrollo.
Por último, dado que en trabajos anteriores relacionados con este proyecto se ha realizado un control de versiones mediante la aplicación GitHub, se ha decidido mantener dicho control. Para ello, se ha reescrito el proceso de instalación descrito en el fichero “README.md”, con el proceso que se va a describir a continuación. Cabe destacar que, al no tener acceso al repositorio previamente creado, se ha creado un repositorio para este proyecto. También cabe mencionar que solo se ha mantenido bajo control de versiones el código fuente, ya que las librerías podrían volver a instalarse mediante el gestor de dependencias “Composer”.
Antes de iniciar con el proceso de instalación, cabe mencionar que para el arranque del entorno durante el desarrollo se ha hecho uso del servidor web Apache y el gestor de base datos MySQL, gestionados desde la herramienta XAMPP.

### XAMPP
En este proyecto se ha utilizado la versión 8.1.2 del programa XAMPP (https://www.apachefriends.org/es/index.html). Para preparar el entorno, es necesario descargarlo e instalarlo, asegurando durante el proceso de instalación que se instalan tanto el servidor MySQL como el intérprete de PHP y la aplicación phpMyAdmin.
Al finalizar la instalación, ejecutar el programa, con el que arrancar tanto el gestor de base de datos MySQL como el servidor Apache, que será aquel que nos permita desplegar la aplicación php. Para arrancar ambos servicios, pulsar el botón “Start” de la interfaz gráfica. Después, para comprobar que funciona, acceder desde el navegador a la ruta localhost/dashboard.
Una vez comprobado que la instalación de XAMPP ha sido correcta, copiar el código de la aplicación en el directorio XAMPP/htdocs, ya que será en dicho directorio donde el servidor Apache busque los ficheros. Dado que puede haber múltiples aplicaciones en el mismo servidor web, se recomienda situar cada aplicación en un subdirectorio de XAMPP/htdocs, como, por ejemplo, XAMPP/htdocs/TFG.


### phpMyAdmin
Con el servidor Apache y MySQL funcionando, accedemos desde el navegador a la ruta localhost/dashboard, desde la que accederemos a un gestor gráfico de la base de datos. Aunque puede utilizarse alguna herramienta gráfica para la gestión de la base de datos, es necesario crear la base de datos desde el panel de administración de php.
Una vez en dicho panel, creamos la base de datos. Para ello, en el menú del lado izquierdo veremos un icono para crear la base de datos, que se llamará “prueba2_tfg_tutorias”. Es necesario que la base de datos se llame de esta forma, ya que es el nombre que usará la aplicación para establecer conexiones con la base de datos.
Una vez creada (aparecerá en el menú de la izquierda), la seleccionamos y pulsamos el botón de importar, que aparecerá en la parte superior de la página. Una vez pulsado dicho botón, nos pedirá que seleccionemos el fichero SQL que queremos importar. Buscamos el fichero SQL localizado con el código de la aplicación. Una vez importado, se crearán las tablas de la base de datos descrita anteriormente. Es importante destacar que la base de datos no tiene ningún dato, ya que la figura del administrador desaparece. 


### Plugin del calendario
El calendario elegido en anteriores versiones de este proyecto es FullCalendar, la versión Standard Bundle. Para su funcionamiento, se hace uso de este plugin mediante el acceso de url.
Para asegurar su correcto funcionamiento, desde la página web de FullCalendar (https://fullcalendar.io/) pulsamos el botón “Get Started”, “Initializating with script tags” y accedemos al enlace del apartado “CDN”. Ahí, encontraremos varios ficheros, y tendremos que modificar el enlace de esos ficheros que figuran en el código de este proyecto. Concretamente, es necesario modificar las versiones en los ficheros “Profesor_menu.php” y “Profesor_consulta_citas.php”.



### Composer 

Composer es un gestor de dependencias de php de código abierto descargable desde su página web (https://getcomposer.org/). En este trabajo se ha utilizado la versión 2.2.6. Para instalar las dependencias y asegurar que no existan problemas de incompatibilidad o colisiones entre las dependencias de las distintas posibles versiones tanto del intérprete de php como de Composer, se recomienda borrar el directorio “vendor” y generarlo de nuevo. En el caso de que el nuevo directorio con las dependencias se genere en el directorio padre del proyecto, simplemente habrá que copiarlo en el directorio del proyecto. El proceso para instalar las dependencias se indica a continuación.
Primero, arrancar el servidor Apache mediante XAMPP. Después, abrir una terminal desde XAMPP y situarnos en la carpeta del proyecto (XAMPP/htdocs/Proyecto), desde donde ejecutaremos el siguiente mandato para ver la versión de Composer que tiene el sistema.
composer -V
Este mandato debería mostrarnos una versión de composer igual o superior a la 2.2.6.
Segundo, es necesario instalar la dependencia del API de Google. Para ello, ejecutamos los siguientes comandos:
composer require google/apiclient
composer update –ignore-platform-reqs
Una vez ejecutados ambos comandos, instalamos Carbon con el siguiente comando
composer require nesbot/carbon
Este paquete de php nos permitirá gestionar las fechas en el proyecto.


### API de Google

Una vez instaladas las dependencias de php, debemos asegurarnos de tener las credenciales adecuadas y configuradas para poder interactuar con Google Calendar desde nuestra aplicación. Para ello, creamos una cuenta de Google que nos permita usar las credenciales de Google.
Una vez creada la cuenta, desde la consola del API de Google (https://console.cloud.google.com/), desde la que podremos gestionar la cuenta y las credenciales. Una vez en la pantalla inicial, creamos un proyecto con el nombre que queramos. Una vez creado el proyecto, lo seleccionamos y, en el menú que aparece a la izquierda de la pantalla, habilitamos el API de Google Calendar en el menú de “API y Servicios”. En ese menú seleccionaremos la opción de “Biblioteca”.
Una vez habilitado el servicio, en el mismo menú de “API y Servicios”, seleccionamos el menú “Credenciales”. En ese menú, pulsamos el botón “CREAR CREDENCIALES”, y ahí seleccionamos “ID de cliente de OAuth”. Es importante seleccionar que es para una aplicación web.
Una vez creada la credencial, en la pantalla de las credenciales, descargamos el fichero json de la credencial que acabamos de crear (botón de descarga a la derecha). Ese fichero json lo copiamos en la carpeta del proyecto.
Una vez copiado ese fichero en la carpeta del proyecto, comprobamos que no existe ningún fichero denominado “token.json”. Si existe, debemos eliminarlo, dado que no debe existir ningún fichero con ese nombre a la hora de generar otro token de acceso a la aplicación.
Dado que el token tiene un tiempo de vida limitado, es posible que la acción descrita a continuación deba repetirse en alguna ocasión. 
Una vez asegurado que no existe ningún token y que existe un fichero denominado “quickstart.php”, en una consola (preferiblemente la de XAMPP), ejecutamos el siguiente mandato para generar un nuevo token de acceso.
php quickstart.php
Este comando debería generar un nuevo fichero “token.json”, con el que podremos usar nuestra aplicación por completo. Una vez generado el token, arrancamos el servidor Apache y el servidor MySQL, pudiendo comprobar cómo funciona correctamente la aplicación.



## Upload
Para gestionar los cambios que se producen en el desarrollo de la aplicación, en trabajos anteriores se decidió integrar la aplicación con la herramienta de control de versiones Git, y más concretamente con la aplicación web GitHub. Debido al gran número de ficheros existentes debido a las dependencias, se ha decidido crear en este trabajo un fichero “.gitignore” para indicar a la herramienta que no debe mantener bajo control los ficheros del directorio “vendor”, donde se almacenan los ficheros de las dependencias. Además, cabe destacar que se ha hecho uso de la aplicación GitHub Desktop para gestionarlo. Sin embargo, también es posible usar la consola de comandos de Windows o un plugin de Visual Studio si el desarrollador lo prefiere.
Para registrar los cambios producidos en la aplicación desde una terminal, es necesario ejecutar los siguientes comandos. Si se usa la interfaz gráfica, ésta irá indicando al usuario los pasos a seguir.
git add -a
git commit -m “mensaje”
git push

