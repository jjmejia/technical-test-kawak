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

_Nota_: El código SQL usado para la creación y llenado inicial de la base de datos se indica en el archivo `bdd.sql`.

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
