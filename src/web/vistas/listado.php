<?php

/**
 * Prueba técnica KAWAK
 * Descargo: HTML creado con asistencia de ChatGPT.
 *
 * @author John Mejía
 */

$bdd = obtener_bdd();

$docs = $bdd->bddQuery(
	'SELECT
	doc_id, doc_nombre, doc_codigo, doc_contenido,
	tip_nombre, tip_prefijo,
	pro_nombre, pro_prefijo
	FROM doc_documento
	LEFT JOIN tip_tipo_doc ON (tip_id = doc_id_tipo)
	LEFT JOIN pro_proceso ON (pro_id = doc_id_proceso)
	ORDER BY doc_id desc'
);

// Valida si viene de un reload y si hay mensajes para mostrar
$session = obtener_session();
$message = $session->getReloadParam('message');
$session->removeReloadParams();

?>
<link rel="stylesheet" type="text/css" href="<?= completar_url('web/recursos/css/listados.css') ?>" />

<?php
if (!empty($message)) {
?>
	<section class="panel" aria-label="Mensajes">
		<!-- Caja informativa -->
		<article class="msg info" id="msg-info" role="status" aria-live="polite">
			<div class="icon" aria-hidden="true">i</div>
			<div class="content">
				<!-- <div class="title">Información</div> -->
				<div class="body"><?= htmlentities($message) ?></div>
			</div>
			<button class="close" aria-label="Ocultar mensaje informativo" data-target="msg-info">✕</button>
		</article>
	</section>
<?php
}
?>

<main>
	<div class="actions">
		<button class="add-btn" onclick="addRecord()">+ Añadir registro</button>

		<div class="search-box">
			<input type="text" id="searchInput" placeholder="Buscar...">
			<button onclick="search()">Buscar</button>
		</div>
	</div>

	<table id="dataTable">
		<thead>
			<tr>
				<th>ID</th>
				<th>Documento</th>
				<th>Proceso</th>
				<th>Tipo</th>
				<th>Código</th>
				<th>Contenido</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>

			<?php
			// Lista cada uno de los registros encontrados
			foreach ($docs as $data) {
				$doc_code = "{$data['tip_prefijo']}-{$data['pro_prefijo']}-{$data['doc_id']}";
			?>

				<tr>
					<td data-label="ID"><?= $data['doc_id'] ?></td>
					<td data-label="Documento" id="docname_<?= $data['doc_id'] ?>"><?= $data['doc_nombre'] ?></td>
					<td data-label="Proceso"><?= $data['pro_nombre'] ?></td>
					<td data-label="Tipo"><?= $data['tip_nombre'] ?></td>
					<td data-label="Codigo"><?= $doc_code ?></td>
					<td data-label="Contenido"><?= $data['doc_contenido'] ?></td>
					<td class="table-actions" data-label="Acciones">
						<button onclick="editRecord(<?= $data['doc_id'] ?>)">Editar</button>
						<button onclick="deleteRecord(<?= $data['doc_id'] ?>)">Eliminar</button>
					</td>
				</tr>

			<?php
			}
			?>
			</tr>
		</tbody>
	</table>
</main>

<form action="" method="post" id="formAction">
	<input type="hidden" name="doc_id" id="doc_id" value="">
</form>

<script>
	function exeAction(action, id) {
		let form = document.getElementById('formAction');
		form.action = action;
		if (typeof id === 'undefined') {
			id = '';
		}
		document.getElementById('doc_id').value = id;
		// alert('Ejecutar: ' + action);
		form.submit();
	}

	function addRecord() {
		let url = '<?= completar_url('listado/adicionar') ?>/';
		exeAction(url);
	}

	function editRecord(id) {
		let url = '<?= completar_url('listado/editar') ?>';
		exeAction(url, id);
	}

	function deleteRecord(id) {
		let name = document.getElementById('docname_' + id).innerHTML;
		if (confirm('¿Deseas eliminar el registro "' + name + '" (ID ' + id + ')?')) {
			let url = '<?= completar_url('listado/eliminar') ?>';
			exeAction(url, id);
		}
	}

	function search() {
		const term = document.getElementById('searchInput').value.toLowerCase();
		const rows = document.querySelectorAll('#dataTable tbody tr');
		rows.forEach(row => {
			const text = row.innerText.toLowerCase();
			row.style.display = text.includes(term) ? '' : 'none';
		});
	}
</script>