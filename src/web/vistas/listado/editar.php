<?php

/**
 * Prueba técnica KAWAK
 * Descargo: HTML creado con asistencia de ChatGPT.
 *
 * @author John Mejía
 */

$page_subtitle = 'Nuevo documento';

$request = new Request();
$session = obtener_session();
$bdd = obtener_bdd();

// Recupera id del registro a editar (al adicionar toma valor "0")
$doc_id = $request->getNumber('doc_id');
$docs = [];

if ($doc_id > 0) {
	$args = [$doc_id];

	$docs = $bdd->bddPrimerRegistro(
		"SELECT
	doc_id, doc_nombre, doc_codigo, doc_contenido,
	tip_id,
	pro_id
	FROM doc_documento
	LEFT JOIN tip_tipo_doc ON (tip_id = doc_id_tipo)
	LEFT JOIN pro_proceso ON (pro_id = doc_id_proceso)
	WHERE doc_id = ?
	ORDER BY doc_id desc",
		$args
	);

	// Modifica titulo de la página
	$page_subtitle = "Editar documento (ID {$doc_id})";
}

// Asegura formato de la variable $docs
if (!is_array($docs) || count($docs) <= 0) {
	$docs = [
		'doc_id' => 0,
		'doc_nombre' => '',
		'doc_codigo' => '',
		'doc_contenido' => '',
		'tip_id' => 0,
		'pro_id' => 0
	];
}

// Obtiene tipos
$tip_tipo_doc = $bdd->bddQuery(
	"SELECT
	tip_id, tip_nombre, tip_prefijo
	FROM tip_tipo_doc
	ORDER BY tip_prefijo desc"
);

// Obtiene procesos
$pro_proceso = $bdd->bddQuery(
	"SELECT
	pro_id, pro_nombre, pro_prefijo
	FROM pro_proceso
	ORDER BY pro_prefijo desc"
);

?>
<link rel="stylesheet" type="text/css" href="<?= completar_url('web/recursos/css/ediciones.css') ?>" />

<main>
	<form id="editForm" action="<?= completar_url('listado/guardar') ?>" method="post">
		<h2><?= $page_subtitle ?></h2>

		<div class="form-group">
			<label for="nombre">Nombre</label>
			<input type="text" id="nombre" name="nombre" value="<?= htmlentities($docs['doc_nombre']) ?>" required>
		</div>

		<div class="form-group">
			<label for="proceso">Proceso</label>
			<select id="proceso" name="proceso" required>
				<?php
				foreach ($pro_proceso as $data) {
					$selected = '';
					if ($data['pro_id'] === $docs['pro_id']) {
						$selected = ' selected';
					}
					echo "<option value=\"{$data['pro_id']}\"{$selected}>({$data['pro_prefijo']}) {$data['pro_nombre']}</option>";
				}
				?>
			</select>
		</div>

		<div class="form-group">
			<label for="tipo">Tipo</label>
			<select id="tipo" name="tipo" required>
				<?php
				foreach ($tip_tipo_doc as $data) {
					$selected = '';
					if ($data['tip_id'] === $docs['tip_id']) {
						$selected = ' selected';
					}
					echo "<option value=\"{$data['tip_id']}\"{$selected}>({$data['tip_prefijo']}) {$data['tip_nombre']}</option>";
				}
				?>
			</select>
		</div>

		<div class="form-group">
			<label for="contenido">Contenido</label>
			<textarea id="contenido" name="contenido" required><?= htmlentities($docs['doc_contenido']) ?></textarea>
		</div>

		<div class="buttons">
			<button type="submit" class="btn btn-primary">Guardar</button>
			<button type="button" class="btn btn-cancel" onclick="CancelEdit()">Cancelar</button>
		</div>

		<input type="hidden" name="doc_id" id="doc_id" value="<?= $docs['doc_id'] ?>">
		<?= csrf_input_token() ?>
	</form>
	</form>
</main>

<script>
	function CancelEdit() {
		window.location.href = '<?= completar_url('listado') ?>';
	}

	document.getElementById('editForm').addEventListener('submit', async function(e) {
		e.preventDefault();

		let formData = new FormData(this);
		const data = Object.fromEntries(formData.entries());

		try {
			const res = await fetch('<?= completar_url('listado/guardar')?>', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(data)
			});

			const json = await res.json();

			console.log(json);

			if (res.ok) {
				// alert(json.mensaje.replace('\\n', '\n') || 'Registro guardado correctamente.');
				CancelEdit();
			} else {
				alert(json.mensaje.replace('\\n', '\n') || 'Error al guardar.');
			}
		} catch (error) {
			alert('Error de conexión con el servidor.');
		}
	});
</script>