<?php

$request = new Request();
$web = new WebSupport();
$session = obtener_session();

$username = $request->get('username');
$password = $request->get('password');


if ($username === 'admin' && $password === '1234') {
	$session->save(['user-auth-ok' => true]);
	$session->save(['user-auth-name' => $username]);
	$web->reload('listado');
	exit;
}

// Si llega a este punto, ha fallado el registro
$vista = 'login';
$datos = ['mensaje' => 'Usuario/Contraseña no validos'];
$web->showContent($vista, $datos);
