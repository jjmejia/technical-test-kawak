<?php

/**
 * Layout base para páginas Web.
 *
 * Prueba técnica KAWAK
 *
 * @author John Mejía
 */

?>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="nofollow" />
	<title>
		Prueba CRUD KAWAK
	</title>
	<link rel="stylesheet" type="text/css" href="web/recursos/css/estilos.css" />
</head>

<body>
	<?php
	// Valida si el usuario está registrado
	$session = obtener_session();
	if ($session->get('user-auth-ok')) {
		// Datos base
		$url_logout = completar_url('logout');
		$username = $session->get('user-auth-name', 'nn');
	?>
		<header>
			<h1>Gestión de documentos</h1>
			<div class="user-info">
				Usuario: <strong><?= $username ?></strong>
				<a href="<?= $url_logout ?>">Logout</a>
			</div>
		</header>
	<?php
	}
	?>
	<?= $viewContents; ?>
</body>

</html>