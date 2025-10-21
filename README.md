# technical-test-kawak
Prueba técnica CRUD de gestión de documentos

Construcción de un CRUD de registro de documentos funcional.

## CARACTERÍSTICAS

* Login de usuario (Usuario y contraseñas establecidas como "admin" y "1234" respectivamente).
* Logout de usuario.
* Tabla o grilla de datos de los documentos.
* Búsqueda de registro de documentos.
* Creación de registro de documentos.
* Edición de registro de documentos.
* Eliminación de registro de documentos.

## MODELO DE DATOS

* Tabla PRO_PROCESO precargada con 5 procesos.
* Tabla TIP_TIPO_DOC precargada con 5 tipos de documentos.
* Tabla DOC_DOCUMENTO es la tabla principal donde se almacenan los registros de los documentos.

El programa debe crear un código único consecutivo para cada documento con el siguiente lineamiento:

	TIP_PREFIJO – PRO_PREFIJO – <Consecutivo único>

Ejemplo de codigo:

	Documento: INSTRUCTIVO DE DESARROLLO
	Proceso: (ING) Ingeniería
	Tipo: (INS) Instructivo
	Código: INS-ING-1

Ejemplo de registro de documento:

	{DOC_ID: 1, DOC_NOMBRE: “INSTRUCTIVO DE DESARROLLO”, DOC_CODIGO: “INS-ING-1”, DOC_CONTENIDO: “texto grande con el contenido del documento”, DOC_ID_TIPO: 1, DOC_ID_PROCESO: 1}

## CONSIDERACIONES

* Si el consecutivo 1 ya fue usado en un documento con el mismo Tipo y Proceso, este número no debe volver a utilizarse.
* En la edición del registro del documento si el tipo o el proceso del documento cambian, se debe recalcular el código.

## Instrucciones de uso

Siga las siguientes instrucciones para ejecutar este proyecto correctamente:

* Habilite PHP en su servidor web (versión sugerida: *8.3* o superior). En caso de necesitar información sobre
  cómo habilitar PHP sobre un servidor web Apache, puede consultar esta página:
  [PHP con Apache sobre Windows](https://micode-manager.blogspot.com/2023/01/php-con-apache-sobre-windows.html).
* Descargue el contenido de este repositorio en un directorio del servidor web.
* Habilite el servidor Apache para que interprete los archivos *.htaccess* incluidos en el proyecto.
* Cree una base de datos en un servidor mariaDB o mySQL.
* Cree las tablas requeridas usando el SQL contenido en el archivo `bdd.sql`.
* Realice una copia o renombre el archivo `src/data/bdd.ini-ejemplo` con nombre `src/data/bdd.ini`. Editelo
  con los datos de acceso a la base de datos. El archivo debe contener la siguiente información:

	 * **servidor**: Path o nombre del servidor donde se encuentra el motor de base de datos.
	 * **bdd**: Nombre dado a la base de datos.
	 * **usuario**: Nombre del usuario autorizado para consultas.
	 * **password**: Contraseña asociada al usuario.

* Abra el archivo `src/index.php` en su navegador web y podrá realizar la consulta de datos.
