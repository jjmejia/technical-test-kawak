<?php

/**
 * Prueba técnica KAWAK
 * Deregistro de usuarios autorizados.
 *
 * @author John Mejía
 */

$request = new Request();
$web = new WebSupport();
$session = obtener_session();
$session->delete('user-auth-ok');

$session->saveReloadParams(['message' => 'Ha cerrado sesión correctamente.']);
$web->reload();
