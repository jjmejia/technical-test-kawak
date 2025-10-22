<?php

/**
 * Layout base para páginas Web.
 *
 * Prueba técnica KAWAK
 * Descargo: HTML creado con asistencia de ChatGPT.
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
	<link rel="stylesheet" type="text/css" href="<?= completar_url('web/recursos/css/estilos.css') ?>" />
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

	<script>
		// Delegación: todos los botones con clase .close ocultan la caja indicada por data-target
		document.addEventListener('click', function(e) {
			const btn = e.target.closest('.close');
			if (!btn) return;
			const id = btn.getAttribute('data-target');
			const el = document.getElementById(id);
			if (!el) return;
			el.classList.add('hide');
		});
	</script>

</body>

</html>