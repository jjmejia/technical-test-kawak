<?php

/**
 * Presenta la página Web solicitada
 *
 * Prueba técnica Kawak
 *
 * @author John Mejía
 */

require_once __DIR__ . '/lib/autoload.php';
require_once __DIR__ . '/lib/functions.php';		// Soporte global
require_once __DIR__ . '/web/lib/functions.php';	// Soporte Web

$web = new WebSupport();
$session = obtener_session();

try {
	// Obtiene elemento solicitado y método
	$accion = obtener_solicitud();
	$metodo = strtolower($_SERVER['REQUEST_METHOD']);

	// Por defecto usa la vista de error
	$vista = 'error';
	$datos = ['mensaje' => "La vista solicitada no existe ({$accion})"];

	// Valida las acciones para determinar la vista a mostrar:
	if ($accion === '' || $accion === 'index.php' || !$session->get('user-auth-ok')) {
		if (!$session->get('user-auth-ok')) {
			// * Visualiza login si no está registrado
			$vista = 'login';
			$datos = ['mensaje' => ''];
		} else {
			// * Visualiza listado de documentos si ya está registrado
			$vista = 'listado';
			$datos = [];
		}
	}
	// Accion por defecto
	else {
		$vista = $accion;
		$datos = [];
	}

	$web->showContent($vista, $datos);
} catch (Exception $e) {
	// Forza mensaje de error a pantalla
	$mensaje = $e->getMessage();
	include implode(DIRECTORY_SEPARATOR, [__DIR__, 'web', 'vistas', 'error.php']);
}
