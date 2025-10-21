<?php

/**
 * Gestiona carga de clases
 *
 * Prueba técnica KAWAK
 *
 * @author John Mejia
 */

// Registra función para carga de Clases.
spl_autoload_register(function ($className) {
	$filename = implode(DIRECTORY_SEPARATOR, [__DIR__, 'clases', $className . '.php']);
	if (file_exists($filename)) {
		include_once $filename;
	} else {
		throw new Exception('No pudo inicializar clase ' . $className);
	}
});
