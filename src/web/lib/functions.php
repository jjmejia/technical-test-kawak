<?php

/**
 * Funciones de soporte para Web
 *
 * Prueba técnica KAWAK
 *
 * @author John Mejía
 */

/**
 * Retorna el path indicado como una URL completa, incluido protocolo y dominio usados.
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

function obtener_session()
{
	return new UserSession('kawa-demo');
}

function csrf_input_token(string $post_name = 'token', bool $force_creation = false)
{
	$session = obtener_session();
	$token = $session->csrf(true);

	return "<input id=\"{$post_name}\" name=\"{$post_name}\" type=\"hidden\" value=\"{$token}\">";
}

function csrf_check_token(string $post_name = 'token')
{
	$request = new Request();
	$session = obtener_session();

	$known_string = $session->csrf();
	$user_string = $request->get($post_name);
	// Si no existe token a validar, da TRUE como respuesta
	$result = ($known_string === false);
	// TRUE si recibe TOKEN y coincide con el valor
	// (solamente aplica para datos recibidos por POST)
	if (!$result && $user_string !== false) {
		// http_response_code(400);
		// exit("Autenticación CSRF fallida ({$post_name})");
		// Existe, valida resultado
		$result = hash_equals($known_string, $user_string);
	}

	return $result;
}