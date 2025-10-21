<?php

/**
 * Funciones de soporte globales
 *
 * Prueba técnica KAWAK
 *
 * @author John Mejía
 */

/**
 * Evalua consulta URI
 */
function obtener_solicitud(): string
{
	$accion = '';

	// Se usa parse_url() para garantizar que recupere solamente el path.
	$uri_base = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	if (substr($uri_base, -1, 1) !== '/') {
		$uri_base .= '/';
	}
	$base = dirname($_SERVER['SCRIPT_NAME']) . '/';
	$len_base = strlen($base);
	if (substr($uri_base, 0, $len_base) === $base) {
		// Recupera cadena de consulta (remueve ultimo "/" usado solo para comparación con $base)
		$accion = substr($uri_base, $len_base, -1);
	}

	return $accion;
}

/**
 * Carga base de datos
 */
function obtener_bdd(): BDDSupport
{
	$bdd = new BDDSupport();

	// Archivo con los datos de conexión
	$filename = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data', 'bdd.ini']);
	if (!$bdd->cargarDatosIni($filename)) {
		throw new Exception('No pudo cargar datos para conectar la base de datos', 31);
	}
	if (!$bdd->bddConectar()) {
		throw new Exception('No pudo conectar la base de datos', 32);
	}

	return $bdd;
}