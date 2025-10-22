<?php

/**
 * Funciones de soporte para Web
 *
 * Prueba técnica KAWAK
 *
 * @author John Mejía
 */

/**
 * Completa una URL relativa convirtiéndola en una URL absoluta basada en el servidor actual.
 *
 * Esta función genera una URL absoluta utilizando el esquema (http o https),
 * el nombre del servidor y el directorio del script actual. Si el parámetro
 * `$path` no comienza con una barra (`/`), se le agrega automáticamente.
 *
 * @param string $path La ruta relativa que se desea completar.
 * @return string La URL absoluta generada.
 */
function completar_url(string $path): string
{
	$url = 'http';
	if (!empty($_SERVER['HTTPS'])) {
		$url = 'https';
	}
	if (substr($path, 0, 1) !== '/') {
		$path = '/' . $path;
	}
	$url .= '://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']) . $path;
	return $url;
}

/**
 * Obtiene una nueva instancia de la clase UserSession (sesión de usuario).
 *
 * @return UserSession Retorna un objeto de la clase UserSession.
 */
function obtener_session()
{
	return new UserSession('kawa-demo');
}

/**
 * Genera un campo de entrada HTML oculto con un token CSRF.
 *
 * @param string $post_name El nombre del atributo "name" del campo de entrada.
 *                          Por defecto es 'token'.
 * @param bool $force_creation Indica si se debe forzar la creación de un nuevo token CSRF.
 *                             Por defecto es false.
 *
 * @return string El código HTML del campo de entrada oculto con el token CSRF.
 */
function csrf_input_token(string $post_name = 'token', bool $force_creation = false)
{
	$session = obtener_session();
	$token = $session->csrf(true);

	return "<input id=\"{$post_name}\" name=\"{$post_name}\" type=\"hidden\" value=\"{$token}\">";
}

/**
 * Verifica la validez de un token CSRF proporcionado por el usuario.
 *
 * Esta función compara un token proporcionado por el usuario con el token
 * almacenado en la sesión para prevenir ataques CSRF (Cross-Site Request Forgery).
 *
 * @param string $user_string El token CSRF proporcionado por el usuario.
 *
 * @return bool Devuelve TRUE si el token es válido o si no existe un token
 *              almacenado en la sesión. Devuelve FALSE si el token proporcionado
 *              no coincide con el almacenado en la sesión.
 */
function csrf_check_token(string $user_string)
{
	$session = obtener_session();

	$known_string = $session->csrf();
	// Si no existe token a validar, da TRUE como respuesta
	$result = ($known_string === false);
	// TRUE si recibe TOKEN y coincide con el valor
	// (solamente aplica para datos recibidos por POST)
	if (!$result && !empty($user_string)) {
		// http_response_code(400);
		// exit("Autenticación CSRF fallida ({$post_name})");
		// Existe, valida resultado
		$result = hash_equals($known_string, $user_string);
	}

	return $result;
}